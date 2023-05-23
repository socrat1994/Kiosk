import React, {useCallback, useEffect, useMemo, useState} from 'react';
import MaterialReactTable from 'material-react-table';
import {Box, Button, Tooltip, IconButton} from '@mui/material';
import {Delete, Edit} from '@mui/icons-material';
import {data, states} from './MakeData';
import FetchData from "./FetchData";
import {getCommonEditTextField} from "./table/getCommonEditTextField";
import {tableHeads} from "./table/tableHeads";
import {CreateNewRow} from "./table/CreatNewRow";
import {useSelector} from "react-redux";
import {forEach} from "react-bootstrap/ElementChildren";
import Error from "./Error";
import {ScaleLoader} from "react-spinners";

let a = [{T: 'i',}, {T: 'no', accessorKey: 'lastName', header: 'Last Name', size: 140,}];
let addbutton;
let i = 0;
let toServer = {add: {}, edit: {}, delete: {}};
let s = '9s41rp';
let serverError = {[s]: {email: 'server email error'}};
let editable = false;
let deletable = false;
let excepts = [];
let submitto = '';
const tableBuilder = ({url}) => {
    const [createModalOpen, setCreateModalOpen] = useState(false);
    const [tableData, setTableData] = useState(() => data);
    const [validationErrors, setValidationErrors] = useState({});
    const [columnssers, setColumnssers] = useState(a);
    const server = useSelector(state => state.settings);
    const [isLoading, setIsLoading] = useState(false);
    const [serverErrors, setServerErrors] = useState(serverError);
    const [errors, setErrors] = useState({});
    const [isLoadingSave, setIsLoadingSave] = useState(false);
    const handleCreateNewRow = (values) => {
        tableData.push(values);
        values.id = 'a' + i;
        i++;
        toServer.add[values.id] = values;
        setTableData([...tableData]);
    };

    const handleSaveRowEdits = ({exitEditingMode, row, values}) => {
        if (!Object.keys(validationErrors).length) {
            tableData[row.index] = values;
            if (toServer?.add[values.id] ?? false) {
                toServer.add[values.id] = values;
            } else {
                let updated = {};
                for (let key in row.original) {
                    if (row.original[key] != values[key] || key == 'id') {
                        updated[key] = values[key];
                    }
                }
                toServer.edit[values.id] = updated;
            }
            setTableData([...tableData]);
            exitEditingMode();
        }
    };

    const handleCancelRowEdits = () => {
        setValidationErrors({});
    };

    const handleDeleteRow = useCallback(
        (row) => {
            if (
                !confirm(`Are you sure you want to delete row with id ${row.getValue('id')}`)
            ) {
                return;
            }
            tableData.splice(row.index, 1);
            if (toServer?.add[row.getValue('id')] ?? false) {
                delete toServer.add[row.getValue('id')];
            } else {
                delete toServer?.edit?.[row.getValue('id')];
                toServer.delete[row.getValue('id')] = {id: row.getValue('id')};
            }
            setTableData([...tableData]);
        },
        [tableData],
    );

    const getCommonEditTextFieldProps = useCallback(
        (cell) => getCommonEditTextField(cell, validationErrors, setValidationErrors, serverErrors),
        [validationErrors, serverErrors],
    );
    useEffect(() => {
        FetchData(server?.urls[url]).then((data) => {
            addbutton = data?.addbutton;
            editable = data?.edit;
            deletable = data?.delete;
            excepts = data?.excepts;
            submitto = data?.submitto;
            setColumnssers(data.columns);
            setTableData(data.data);
            setIsLoading(true);
            setErrors(data?.errors ?? false);
        })
    }, [url]);

    function handleSave(url, dataTo) {
        setIsLoadingSave(true);
        FetchData(url, {method: 'POST', formData: dataTo}).then(data => {
            let filteredArray = {};
            setServerErrors(data);
            ['add', 'edit', 'delete'].forEach((item) => {
                Object.keys(toServer?.[item])?.forEach(key => {
                    if (data && !data?.hasOwnProperty(key)) {
                        delete toServer[item][key];
                    }
                });
            });
            setErrors(data?.errors ?? false);
            setIsLoadingSave(false);
        })
    }

    let columns = tableHeads(columnssers, getCommonEditTextFieldProps);
    let addColumns = columns?.filter((_, index) => !excepts?.includes(index));

    if (isLoading) {
        return (
            <>
                {isLoadingSave && <ScaleLoader/>}
                <Error place={'common'} errors={errors}/>
                <MaterialReactTable
                    displayColumnDefOptions={{
                        'mrt-row-actions': {
                            muiTableHeadCellProps: {
                                align: 'center',
                            },
                            size: 120,
                        },
                    }}
                    muiTableBodyCellProps={{
                        align: 'center',
                    }}
                    muiTableBodyRowProps={rowData => serverErrors?.[rowData.row.original.id]?.errors ? ({
                        style: {
                            backgroundColor: "red"
                        }
                    }) : ''}
                    columns={columns}
                    data={tableData}
                    editingMode="modal" //default
                    enableColumnOrdering
                    enableEditing={editable || deletable}
                    onEditingRowSave={handleSaveRowEdits}
                    onEditingRowCancel={handleCancelRowEdits}
                    renderRowActions={({row, table}) => (
                        <Box sx={{display: 'flex', gap: '1rem'}}>
                            {editable ? (<Tooltip arrow placement="left" title="Edit">
                                <IconButton onClick={() => table.setEditingRow(row)}>
                                    <Edit/>
                                </IconButton>
                            </Tooltip>) : ''}
                            {deletable ? (<Tooltip arrow placement="right" title="Delete">
                                <IconButton color="error" onClick={() => handleDeleteRow(row)}>
                                    <Delete/>
                                </IconButton>
                            </Tooltip>) : ''}
                        </Box>
                    )}
                    renderTopToolbarCustomActions={() => (
                        <>
                            {(editable || deletable) ?
                                <Button color="secondary" onClick={() => handleSave(submitto, JSON.stringify(toServer))}
                                        variant="contained">
                                    Save
                                </Button> : ''}
                            {addbutton ?
                                <Button color="secondary" onClick={() => setCreateModalOpen(true)} variant="contained">
                                    {addbutton}
                                </Button>
                                : ""}
                        </>)}
                />
                <CreateNewRow
                    columns={addColumns}
                    open={createModalOpen}
                    onClose={() => setCreateModalOpen(false)}
                    onSubmit={handleCreateNewRow}
                />
            </>
        );
    }
};

export default tableBuilder;

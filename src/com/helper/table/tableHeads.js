import {MenuItem} from "@mui/material";
import React from 'react';

export const tableHeads = (columnssers , getCommonEditTextFieldProps) => {
    return columnssers.map(columnsser => {
        if (columnsser.T === 'i') {
            let Columns = {
                accessorKey: 'id',
                header: 'ID',
                enableColumnOrdering: false,
                enableEditing: false, //disable editing on this column
                enableSorting: false,
                size: 80
            }
            return {...Columns, ...columnsser};
        } else if (columnsser.T === 'no') {
            return {
                ...columnsser,
                muiTableBodyCellEditTextFieldProps: ({cell}) => ({
                    ...getCommonEditTextFieldProps(cell),
                }),
            };
        } else if (columnsser.T === 's') {
            return {
                ...columnsser,
                muiTableBodyCellEditTextFieldProps: {
                    select: true,
                    children: columnsser.options.map(option => (
                        <MenuItem key={option} value={option}>
                            {option}
                        </MenuItem>
                    )),
                },
            };
        }
    });
};

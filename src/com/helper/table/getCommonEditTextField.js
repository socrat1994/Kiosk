import {useEffect} from "react";
import {removeBeforeChar} from "../Functions";

export const getCommonEditTextField = (cell, validationErrors, setValidationErrors, serverErrors) => {
    let row = serverErrors?.[cell.row.original.id]?.errors;
    let sId = removeBeforeChar(cell.id, '_');
    return {
        error: !!validationErrors[cell.id] || !!(row?row[sId]:false),
        helperText: validationErrors[cell.id]??(row?row[sId]:''),
        onBlur: (event) => {
            const isValid =
                cell.column.id === 'email'
                    ? validateEmail(event.target.value)
                    : cell.column.id === 'age'
                        ? validateAge(+event.target.value)
                        : validateRequired(event.target.value);
            if (!isValid) {
                //set validation error for cell if invalid
                setValidationErrors({
                    ...validationErrors,
                    [cell.id]: `${cell.column.columnDef.header} is required`,
                });
            } else {
                delete validationErrors[cell.id];
                row ? row[sId] ? delete row[sId]:'':'';
                setValidationErrors({
                    ...validationErrors,
                });
            }
        },
    };
};

 const validateRequired = (value) => !!value.length;
 const validateEmail = (email) =>
    !!email.length &&
    email
        .toLowerCase()
        .match(
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
        );
 const validateAge = (age) => age >= 18 && age <= 50;

export function validate(event) {
    const isValid =
        event.target.name === 'email'
            ? validateEmail(event.target.value)
            : event.target.name === 'age'
                ? validateAge(+event.target.value)
                : validateRequired(event.target.value);
    if (!isValid) {
        //set validation error for cell if invalid
        event.target.style.color = 'red';
    } else {

    }
}
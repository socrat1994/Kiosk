import React ,{useEffect, useState}from 'react';
import Error from './helper/Error';

const TwoColumn = (props) => {
    const [value, setValue] = useState("");

    useEffect(() => {
        setValue("");
    },[props.url]);

    return (
        <div className="form-group row p-1 m-1 text-lang"  key={props.name}>
            <label className='col-sm-3 col-form-label' htmlFor={props.name + 'id'}>{props.label}</label>
            <div className="col-sm-9 p-0">
                <input className={props?.errors[props?.name]?'form-control is-invalid':"form-control"}
                       type={props.type}
                       value={value}
                       name={props.name}
                       onChange={(event) => setValue(event.target.value)}
                       id={props.name + 'id'}/>
                <Error place={props?.name} errors={props?.errors}/>
            </div>
        </div>
    );
}
export default TwoColumn;
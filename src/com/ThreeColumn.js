import React, {useEffect, useState} from 'react';
import Error from "./helper/Error";


const ThreeColumn = (props) => {

    const [isShown, setIsShown] = useState(false);
    const [value, setValue] = useState('');

    useEffect(() => {
        setValue('');
        setIsShown(false);
    },[props.url]);

    return (
        <div className="form-group row p-1 m-1" >
            <label className='col-sm-3 col-form-label text-lang' htmlFor={props.name + 'id'}>{props.label}</label>
            <div className="col-sm-9 row d-flex">
                <div className='col-10 p-0'>
                    <input className={props?.errors[props?.name]?'form-control is-invalid':"form-control"}
                           id={props.name + 'id'}
                           type={isShown ? 'text' : 'password'}
                           name={props.name}
                           value={value}
                           onChange={(event) => setValue(event.target.value)}
                    />
                    <Error place={props?.name} errors={props?.errors}/>
                </div>
                <div className='col-2 p-0'>
                    <input id={props.bname} type={props.btype}
                           className='btn btn-sm'
                           value={!isShown ? props.dic.show : props.dic.hid}
                           onClick={() => setIsShown(!isShown)}
                           />
                </div>

            </div>
        </div>
    );
}
export default ThreeColumn;
import React, {useState, useEffect, createContext} from 'react';
import TwoColumn from "./com/TwoColumn";
import ThreeColumn from "./com/ThreeColumn";
import FetchData from "./com/helper/FetchData";
import Error from "./com/helper/Error";
import { ScaleLoader } from 'react-spinners';
import {useDispatch, useSelector} from "react-redux";
import { useNavigate } from "react-router-dom";

const columns = {
    2:TwoColumn,
    3: ThreeColumn
}
let head = '';
let submit = '';
let submitto = '';

const Formbuilder = ({url}) => {
    const [formState, setFormState] = useState({});
    const [errors, setErrors] = useState({});
    const [form, setForm] = useState({});
    const [isLoading, setIsLoading] = useState(false);
    const server = useSelector(state => state.settings);
    const history = useNavigate();
    const dispatch = useDispatch();


    useEffect(() => {
        setErrors(false);
        FetchData(server?.urls[url]).then((data)=>{
        head = data?.form?.head;
        submit = data?.form?.submit;
        submitto = data?.form?.submitto;
        setErrors(data?.errors??false);
        setForm(data);
    })
    }, [server,url]);
    function  handlesubmit(url, options, event) {
        event.preventDefault();
        setIsLoading(true);
        FetchData(url, options).then(data => {
            setErrors(data?.errors??false);
            if (data?.redirect) {
                history(data.redirect);
            }
            if (data?.update) {
                dispatch({
                    type: "UPDATE_SETTINGS",
                    payload: data
                });
                history(data.redirect);
            }
            setIsLoading(false);
        })
    }
    function handleInputChange(event) {
        const {name, value} = event.target;
        setFormState(prevState => ({...prevState, [name]: value}));
    }

    return (
        <div className='col col-sm-12 col-md-8 m-auto'>
            <div className="card" >
                <div className="card-header">
                    <div className="">{head}</div>
                    <Error place={'commo'} errors={errors}/>
                </div>
                <div className='card-body'>
                <form method="post" id={'formy'}>
                    {(form?.inputs??[]).map(input => {const  ColumnCount = columns[input.column];
                        return < ColumnCount key={input.name} {...input} errors={errors} url={url}/>;})}
                    <div  className="">
                        <div  className="">
                            <input type="submit" value={submit} className='btn btn-primary'
                                   onClick={(event) =>
                                   {handlesubmit(submitto, {method:'POST'}, event); }} disabled={errors?.common?.stop??false}/>
                            <Error place={'common'} errors={errors}/>
                            {isLoading && <ScaleLoader />}

                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    );
}

export default Formbuilder;
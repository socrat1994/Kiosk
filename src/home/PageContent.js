import React, {useState} from 'react';
import HomeContent from "./HomeContent";
import {Route, Routes, Navigate} from "react-router-dom";
import FormBuilder from "../FormBuilder";
import tableBuilder from '../com/helper/tableBuilder';

let Components = {
    1: HomeContent,
    2: FormBuilder,
    3: tableBuilder
};
const PageContent = ({server}) => {
    return (
        <main className="text-center container mt-5 mb-5 ">
            <div className='m-1'>
            <Routes>
                {(server?.links??[]).map((link) => {
                    const Component = Components[link.component];
                    return <Route key={link.to} path={link.to} element={<Component key={link.to} url={link.to} content={server?.content}/>}/>
                })}
                {(server?.server ?? false) && <Route path="*" element={<Navigate to={server?.default??'/'} />} /> }
            </Routes>
            </div>
        </main>
    );
}
export default PageContent;
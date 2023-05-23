import React, {useEffect, useState} from 'react';
import PageContent from './PageContent';
import PageHead from "./PageHead";
import {BrowserRouter as Router, Route, Routes, Link} from 'react-router-dom';
import PageFooter from "./PageFooter";
import FetchData from "../com/helper/FetchData";
import {settingsReducer} from "../com/helper/GlobalStore";
import {useSelector, useDispatch, Provider} from "react-redux";
import {createStore} from 'redux';

let url = window.home;

const HomePage = () => {
    const dispatch = useDispatch();
    const server = useSelector(state => state.settings);

    useEffect(() => {
        FetchData(url).then(data => {
            dispatch({
                type: "UPDATE_SETTINGS",
                payload: data
            });
        })
    }, []);
    return (
            <Router>
                <PageHead links={server?.links ?? []}/>
                <PageContent server={server ?? []}/>
                <PageFooter footer={server?.footer ?? []}/>
            </Router>
    );
};

export default HomePage;

import React from 'react';
import ReactDOM from 'react-dom/client';
import HomePage from "./home/HomePage";
import {settingsReducer} from "./com/helper/GlobalStore";
import {useSelector, useDispatch, Provider} from "react-redux";
import {createStore} from 'redux';
import client from './index1'

const store = createStore(settingsReducer);

const container = document.getElementById('root');
const root = ReactDOM.createRoot(container);
root.render(<client/>
    /*<Provider store={store}>
        <HomePage/>
    </Provider>*/ );
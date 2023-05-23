const initialState = {
    settings: {
    links: [{to: '/sign/', label: 'sign in', component: 2},
        {to: '/signup/', label: 'sign up', component: 3},
        {to: '/', label: 'home', component: 1}],
        footer: [{text:'We offer high-quality car maintenance and repair services at affordable prices.'}]
}
};

export function settingsReducer(state = initialState, action) {
    switch (action.type) {
        case "UPDATE_SETTINGS":
            return {
                ...state,
                settings: action.payload
            };
        default:
            return state;
    }
}
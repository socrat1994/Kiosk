import React from "react";

const Error = ({place, errors}) => {
    return (
        errors[place]?.i??false ?<p className='alert-success text-center'>{errors[place].i??''}</p>
            : <p className='alert-danger text-center'>{errors[place]??''}</p>
    );
}
export default Error;
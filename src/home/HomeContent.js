import React from 'react';

const HomeContent = ({content}) => {
return (
    <div><h1>{content?.header}</h1>{content?.text}</div>
);
}
 export default HomeContent;
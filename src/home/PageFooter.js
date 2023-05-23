import React ,{memo}from "react";

const PageFooter = ({footer}) => {
    return (
        <footer className="text-center p-4 bg-light">
            <p>{footer[0]?.text}</p>
        </footer>
    );
}
export default memo(PageFooter);
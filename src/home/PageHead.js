import React ,{memo}from "react";
import {Container, Nav, Navbar, NavDropdown} from "react-bootstrap";
import {Link} from "react-router-dom";
import FetchData from "../com/helper/FetchData";
import {useDispatch, useSelector} from "react-redux";

const domain = window.domain;
const PageHead = ({links}) => {
    const dispatch = useDispatch();
    const server = useSelector(state => state.settings);

    function update(url)
    {
        FetchData(url).then(data => {
            dispatch({
                type: "UPDATE_SETTINGS",
                payload: data
            });
        })

    }

    function  changeLang(url) {
        FetchData(url, {method:'GET'}).then((data) => {
            FetchData(url).then(data => {
                dispatch({
                    type: "UPDATE_SETTINGS",
                    payload: data
                });
                if (url === domain + 'lang?lang=ar') {
                    document.documentElement.lang = "ar";
                    document.documentElement.dir = "rtl";
                } else {
                    document.documentElement.lang = "en";
                    document.documentElement.dir = "ltr";
                }
            })
        })
    }

    return (
        <header>
            <Navbar bg="light" expand="lg">
                <Container>
                    <Navbar.Brand as={Link} to='/'>Kiosk</Navbar.Brand>
                    <Navbar.Toggle aria-controls="basic-navbar-nav"/>
                    <Navbar.Collapse id="basic-navbar-nav">
                        <Nav className="me-auto">
                            <NavDropdown title="&#x1F310;" id="basic-nav-dropdown">
                                <NavDropdown.Item onClick={() => changeLang(domain + 'lang?lang=ar')}>عريي</NavDropdown.Item>
                                <NavDropdown.Item onClick={() => changeLang(domain + 'lang?lang=en')}>English</NavDropdown.Item>
                            </NavDropdown>
                            {
                                links.map((link, index) => {
                                    return <Nav.Link key={link.label+index} as={Link} to={link.to} >
                                        {link.label}
                                    </Nav.Link>;})
                            }
                            {
                               server?.user?.name ? (<NavDropdown title={server.user.name} id="basic-nav-dropdown">
                                   <NavDropdown.Item onClick={() => update(server.user.url)}>{server.user.lable}</NavDropdown.Item>
                               </NavDropdown>):''
                            }

                        </Nav>
                    </Navbar.Collapse>
                </Container>
            </Navbar>
        </header>
    );
}
export default memo(PageHead);
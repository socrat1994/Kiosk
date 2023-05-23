import React from 'react';
import { Link } from 'react-router-dom';

const App = ({ links }) => {
  return (
    <div>
      {links.map((link, index) => (
        <Link key={index} to={link.to}>
          {link.text}
        </Link>
      ))}
    </div>
  );
};

export default App;

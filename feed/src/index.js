import 'babel-polyfill';

import React from 'react'
import ReactDOM from 'react-dom';

import './styles/_css/stylesheet.css';
import '../public/assets/stylesheet.scss';

const Box = (props) => (
  <div>
    <h1>Bcvcco</h1>
  </div>
);

ReactDOM.render(
  <Box/>,
  document.getElementById('app')
);



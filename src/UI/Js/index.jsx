import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import { configureStore } from '@reduxjs/toolkit';
import createUser from './redux/createUser';
import search from './redux/search';
import App from './App';

const store = configureStore({
  reducer: { search, createUser },
});

export default store;

ReactDOM.render(
  <Provider store={store}>
    <App />
  </Provider>,
  document.querySelector('#app'),
);

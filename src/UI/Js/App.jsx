import React from 'react';
import {
  BrowserRouter as Router,
  Switch,
  Route,
} from 'react-router-dom';
import CreateUser from './Components/CreateUser';
import SearchUserForm from './Components/SearchUserForm';

export default () => (
  <Router>
    <div className="directory">
      <Switch>
        <Route path="/" exact>
          <SearchUserForm />
        </Route>
        <Route path="/create" exact>
          <CreateUser />
        </Route>
      </Switch>
    </div>
  </Router>
);

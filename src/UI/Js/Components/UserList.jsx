import React from 'react';
import PropTypes from 'prop-types';

const UserList = ({ users }) => {
  const items = users.map((user) => (
    <li key={user.id} className="list-group-item list-group-item-action flex-column align-items-start">
      <div className="d-flex w-100 justify-content-between">
        <h5 className="mb-1">{ user.name }</h5>
      </div>
      <ul>
        { Object.keys(user.properties).map((prop) => (
          <li key={prop}>
            { prop }
            {' '}
            <b>{ user.properties[prop] }</b>
          </li>
        ))}
      </ul>
      <small><i>{user.id}</i></small>
    </li>
  ));

  return (
    <ul className="list-group" id="userList">
      { items }
    </ul>
  );
};

UserList.propTypes = {
  users: PropTypes.array.isRequired,
};

export default UserList;

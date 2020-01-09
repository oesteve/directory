import React from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { Link } from 'react-router-dom';
import _ from 'underscore';
import TextInput from './TextInput';
import { search } from '../redux/search';
import UserList from './UserList';

export default () => {
  const dispatch = useDispatch();
  const { results, fetching } = useSelector((state) => state.search);

  const lazySearch = _.debounce((value) => {
    dispatch(search(value));
  }, 500);

  const fetchingMessage = fetching ? (
    <div className="spinner-border" role="status">
      <span className="sr-only">Loading...</span>
    </div>
  ) : null;
  const userList = results.length
    ? (<UserList users={results} />)
    : (<i>No se han encontrado resultados</i>);

  return (
    <div className="card">
      <div className="card-body">
        <h5 className="card-title">Buscador de usuarios</h5>
        <div className="pb-3 pt-3">
          <form
            className="form"
            onSubmit={(event) => {
              event.preventDefault();
            }}
          >
            <TextInput
              name="query"
              required={false}
              placeholder="Buscar ..."
              onChange={(value) => { lazySearch(value); }}
            />
          </form>
          { fetchingMessage }
          { userList }
        </div>
        <Link className="btn btn-outline-primary float-right" to="/create">Añadir Usuarios</Link>
      </div>
    </div>
  );
};

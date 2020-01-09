import React from 'react';

import { useDispatch, useSelector } from 'react-redux';
import { Link } from 'react-router-dom';
import TextInput from './TextInput';

import {
  addProp, removeProp, createUser, setName, setPropName, setPropValue,
} from '../redux/createUser';
import PropertyInput from './PropertyInput';


const CreateUser = () => {
  const dispatch = useDispatch();
  const {
    name, props, loading, message, error,
  } = useSelector((state) => state.createUser);

  // eslint-disable-next-line react/destructuring-assignment,react/prop-types
  const propInputs = props.map((prop, index) => (
    <PropertyInput
      name={`props.${index}`}
      key={index}
      onRemove={() => { dispatch(removeProp(index)); }}
      propName={prop.name}
      propValue={prop.value}
      onNameChange={(value) => { dispatch(setPropName({ index, value })); }}
      onValueChange={(value) => { dispatch(setPropValue({ index, value })); }}
    />
  ));

  const submitButton = loading
    ? (
      <button className="btn btn-primary float-right" type="button" disabled>
        <span className="spinner-border spinner-border-sm" role="status" aria-hidden="true" />
        {' '}
Guardando...
      </button>
    )
    : (
      <button type="submit" className="btn btn-primary float-right" name="submit">
            Guardar
      </button>
    );

  const messageDisplay = message
    ? (
      <div className="alert alert-success" role="alert">
        { message }
      </div>
    )
    : null;

  const errorDisplay = error
    ? (
      <div className="alert alert-danger" role="alert">
        { error }
      </div>
    )
    : null;


  return (
    <div className="card">
      <div className="card-body">
        <h5 className="card-title">Crear un nuevo usuario</h5>
        <form className="form" onSubmit={(event) => { event.preventDefault(); dispatch(createUser()); }}>
          <div className="pb-3 pt-3">
            { errorDisplay }
            { messageDisplay }
            <TextInput
              required
              label="Nombre"
              name="name"
              value={name}
              placeholder="Nombre del usuario"
              onChange={(value) => dispatch(setName(value))}
            />
            {/* eslint-disable-next-line jsx-a11y/label-has-associated-control */}
            { propInputs.length ? <label>Características:</label> : null}
            { propInputs }
            <button
              type="button"
              className="btn btn-light btn-sm"
              onClick={(event) => { event.preventDefault(); dispatch(addProp()); }}
            >
              {/* eslint-disable-next-line react/prop-types,react/destructuring-assignment */}
              { (props.length > 0)
                ? 'Añadir más características'
                : 'Añadir características'}
            </button>

          </div>
          <div>
            <Link className="btn btn-sm btn-outline-primary float-left" to="/">Volver al Buscador</Link>
            { submitButton }
          </div>
        </form>
      </div>
    </div>
  );
};

export default CreateUser;

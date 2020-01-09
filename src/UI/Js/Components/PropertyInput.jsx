import React from 'react';
import PropTypes from 'prop-types';

const PropertyInput = ({
  name,
  onRemove,
  onNameChange,
  onValueChange,
}) => (
  <div className="form-group form-row">
    <div className="col">
      <input
        required
        name={`${name}.name`}
        type="text"
        placeholder="Característica"
        className="form-control form-control-sm"
        onChange={((event) => { onNameChange(event.target.value); })}
      />
    </div>
    <div className="col">
      <input
        required
        name={`${name}.value`}
        type="text"
        placeholder="Valor"
        className="form-control form-control-sm"
        onChange={((event) => { onValueChange(event.target.value); })}
      />
    </div>
    <div className="col">
      <button
        type="submit"
        className="btn btn-outline-danger btn-sm"
        onClick={((event) => { event.preventDefault(); onRemove(); })}
      >
Eliminar
      </button>
    </div>
  </div>
);


PropertyInput.propTypes = {
  name: PropTypes.string.isRequired,
  onRemove: PropTypes.func.isRequired,
  onNameChange: PropTypes.func.isRequired,
  onValueChange: PropTypes.func.isRequired,
};

export default PropertyInput;

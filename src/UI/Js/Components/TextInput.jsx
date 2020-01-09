import React from 'react';
import PropTypes from 'prop-types';

const TextInput = ({
  name, value = null, label = null, placeholder, onChange, required = true,
}) => {
  const labelTag = label ? (
    <label htmlFor="exampleInputEmail1">
      { label }
:
    </label>
  ) : null;

  return (
    <div className="form-group">
      { labelTag }
      <input
        required={required}
        type="text"
        name={name}
        className="form-control"
        placeholder={placeholder}
        onChange={(event) => { event.preventDefault(); onChange(event.target.value); }}
      />
    </div>
  );
};

TextInput.propTypes = {
  name: PropTypes.string.isRequired,
  label: PropTypes.string,
  placeholder: PropTypes.string.isRequired,
  onChange: PropTypes.func.isRequired,
  required: PropTypes.bool.isRequired,
};

TextInput.defaultProps = {
  label: null,
};

export default TextInput;

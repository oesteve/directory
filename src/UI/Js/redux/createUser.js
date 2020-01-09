import { createSlice } from '@reduxjs/toolkit';
import * as directory from '../api/directory';

const initialState = {
  fetching: false,
  message: null,
  error: null,
  name: '',
  props: [{ name: '', value: '' }],
};

const userSlice = createSlice({
  name: 'user',
  initialState,
  reducers: {
    setMessage(state, action) {
      state.message = action.payload;
    },
    clearMessage(state) {
      state.message = null;
    },
    setError(state, action) {
      state.error = action.payload;
    },
    clearError(state) {
      state.error = null;
    },
    setName(state, action) {
      state.name = action.payload;
    },
    removeProp(state, action) {
      state.props.splice(action.payload.index, 1);
    },
    addProp(state) {
      state.props.push({
        name: '',
        value: '',
      });
    },
    setPropName(state, action) {
      state.props[action.payload.index].name = action.payload.value;
    },
    setPropValue(state, action) {
      state.props[action.payload.index].value = action.payload.value;
    },
    setFetching(state, action) {
      state.fetching = action.payload;
    },
    clear(state) {
      state.name = '';
      state.props = [{ name: '', value: '' }];
    },
  },
});

export const { actions, reducer } = userSlice;

export const {
  setName,
  removeProp,
  addProp,
  setFetching,
  setPropValue,
  setPropName,
  setMessage,
  setError,
  clear,
  clearMessage,
  clearError,
} = actions;


export function addMessage(message) {
  return (dispatch) => {
    dispatch(setMessage(message));
    setTimeout(() => {
      dispatch(clearMessage());
    }, 2000);
  };
}

export function addError(message) {
  return (dispatch) => {
    dispatch(setError(message));
    setTimeout(() => {
      dispatch(clearError());
    }, 2000);
  };
}


export function createUser() {
  return (dispatch, getState) => {
    dispatch(setFetching(true));

    const { createUser: { name, props } } = getState();
    const objectProps = {};
    props.forEach((prop) => {
      objectProps[prop.name] = prop.value;
    });

    directory.createUser(name, objectProps)
      .then(({ id }) => {
        dispatch(setFetching(false));
        dispatch(addMessage(`${name} ha sido añadido con id ${id}`));
        dispatch(clear());
      })
      .catch((err) => {
        dispatch(setFetching(false));
        dispatch(addError(err.message));
      });
  };
}

export function deleteUser(userId) {
  return (dispatch) => {
    dispatch(setFetching(true));
    directory.remove(userId)
      .then(() => {
        dispatch(setFetching(false));
        dispatch(addMessage('Usuario eliminado'));
      })
      .catch((err) => {
        dispatch(setFetching(false));
        dispatch(addError(err.message));
      });
  };
}

export default reducer;

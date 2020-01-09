import { createSlice } from '@reduxjs/toolkit';
import * as directory from '../api/directory';

const searchSlice = createSlice({
  name: 'search',
  initialState: {
    query: '',
    fetching: false,
    errors: [],
    results: [],
  },
  reducers: {
    setQuery(state, action) {
      state.query = action.payload;
    },
    setFetching(state, action) {
      state.fetching = action.payload;
    },
    setResults(state, action) {
      state.results = action.payload;
      state.fetching = false;
    },
  },
});

const { actions, reducer } = searchSlice;
export const { setQuery, setFetching, setResults } = actions;

// Async actions
export const search = (query) => (dispatch) => {
  dispatch(setFetching(true));
  directory.search(query)
    .then(((result) => {
      dispatch(setResults(result));
    }))
    .catch(() => {
      dispatch(setFetching(false));
      dispatch(setResults([]));
    });
};

export default reducer;

import axios from 'axios';

export function createUser(name, properties) {
  return axios.post('/api/user', { name, properties })
    .then((response) => response.data)
    .catch((err) => {
      throw new Error(err.response.data.message);
    });
}

export function update(id, name, properties) {
  return axios.put('api/user', {
    id,
    name,
    properties,
  })
    .then((response) => response.data)
    .catch((err) => {
      throw new Error(err.response.data.message);
    });
}

export function remove(userId) {
  return axios.delete(`/api/user/${userId}`)
    .then((response) => response.data)
    .catch((err) => {
      throw new Error(err.response.data.message);
    });
}

export function search(query) {
  return axios.get('/api/user', { params: { query } })
    .then((response) => response.data);
}

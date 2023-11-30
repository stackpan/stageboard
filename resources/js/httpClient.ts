import axios from 'axios'

export default axios.create({
  baseURL: 'http://localhost/api',
  headers: {
    'Content-Type': 'application/json'
  }
})

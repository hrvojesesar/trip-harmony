import axios from 'axios'

export const http = () => {
    let options = {
        baseURL: 'http://localhost:8000',
        headers: {}
    }

    if(localStorage.getItem('token')) {
        options.headers.Authorization = `Bearer ${localStorage.getItem('token')}`
    }
    
    return axios.create(options)
}
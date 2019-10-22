/* eslint-disable */
import axios from 'axios';

export default function execute() {
    axios.interceptors.request.use(function(response) {
        console.log(response)
    });
}
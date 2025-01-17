import axios from 'axios'

export default {
    methods: {
        async callApi(method, url, dataObj) {
            try {
                return await axios({
                    method: method,
                    url: url,
                    data: dataObj,
                })
            }
            catch (e) {
                return e.response
            }
        },
    }
}
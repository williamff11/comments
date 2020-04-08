import axios from 'axios'
import { URL_BASE } from '../../../config/configs'

const RESOURCE = 'comments'

export default {
    loadComments(context, params) {
        context.commit('CHANGE_PRELOADER', true)

        axios.get(`${URL_BASE}${RESOURCE}`, { params })
            .then(response => context.commit('LOAD_COMMENTS', response.data))
            .catch(errors => console.log(errors))
            .finally(() => context.commit('CHANGE_PRELOADER', false))
    },

    storeComment(context, params) {
        context.commit('CHANGE_PRELOADER', true)

        return new Promise((resolve, reject) => {
            axios.post(`${URL_BASE}${RESOURCE}`, params)
                .then(response => resolve())
                .catch(error => {
                    context.commit('CHANGE_PRELOADER', false)
                    reject(error.response)
                })
        })
    },

}

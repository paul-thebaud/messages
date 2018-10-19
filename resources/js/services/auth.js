export default {
    token() {
        return localStorage.getItem('token');
    },
    guest() {
        return localStorage.getItem('token') === null;
    }
};

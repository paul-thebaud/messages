export default class ApiError {
    constructor(code = 200, data = {}) {
        this.code    = code;
        this.message = data.message || '';
        this.errors  = data.errors || {};
    }

    isValid() {
        return this.code >= 200 && this.code < 300;
    }

    isInvalid() {
        return this.code === 422;
    }

    isForbidden() {
        return this.code === 403;
    }

    isUnauthenticated() {
        return this.code === 401;
    }

    isUnknown() {
        return !this.isInvalid() && !this.isForbidden() && !this.isUnauthenticated();
    }

    has(key) {
        return this.errors.hasOwnProperty(key);
    }

    get(key) {
        return (this.errors[key] || []).join(' ');
    }
}

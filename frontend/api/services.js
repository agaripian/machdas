import cookie from '@/utils/cookie';

const getHeaders = () => {
  const authToken = cookie.getCookie('auth_token');
  const customHeaders = {
    'Content-Type': 'application/json',
  };

  if (authToken) {
    customHeaders.auth_token = authToken;
  }

  return new Headers(customHeaders);
};

export default {
  beFetch(url, options = {}) {
    return fetch(url, {
      headers: getHeaders(),
      ...options,
    });
  },

  login(credentials) {
    return this.beFetch('api/index.php/auth/login', {
      method: 'POST',
      body: JSON.stringify(credentials),
    });
  },

  getAllImages(userId) {
    return this.beFetch(`api/index.php/user/${userId}/getallimages`);
  },

  addImage(userId, image) {
    return this.beFetch(`api/index.php/user/${userId}/addimage`, {
      method: 'POST',
      body: JSON.stringify(image),
    });
  },
};

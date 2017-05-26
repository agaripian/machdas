<template>
  <div class="ev-login col-sm-4 offset-sm-4">
    <spinner v-show="loggingIn" message="Logging in..."></spinner>
    <div class="alert alert-danger" v-if="error">
      <p>{{ error }}</p>
    </div>
    <div class="form-group">
      <input
        type="text"
        data-id="login.username"
        class="form-control js-login__username"
        placeholder="Enter your email address"
        v-model="credentials.username"
      >
    </div>
    <div class="form-group">
      <input
        type="password"
        class="form-control js-login__password "
        placeholder="Enter your password"
        v-model="credentials.password"
      >
    </div>
    <button
      data-id="login.submit"
      class="btn btn-primary solid blank js-login__submit"
      @click="submit()"
    >
      Login
    </button>
    <br><br><br>
    <a href="#">Forgot your password?</a><br>
    Don’t have an account? &nbsp;<a href="#signup">Sign up here.</a>

  </div>
</template>

<script>
import Spinner from '@/components/common/Spinner';
import services from '@/api/services';
import cookie from '@/utils/cookie';

export default {
  name: 'login',
  components: { Spinner },
  data() {
    return {
      credentials: {
        user_name: '',
        password: '',
      },
      loggingIn: false,
      error: '',
    };
  },
  methods: {
    submit() {
      cookie.deleteCookie('auth_token');
      this.error = '';
      this.loggingIn = true;
      const credentials = {
        user_name: this.credentials.username,
        password: this.credentials.password,
      };

      services.login(credentials)
      .then(
      (response) => {
        if (response.status !== 200) {
          this.error = 'Username and password do not match. Please try again.';
          return;
        }
        response.json().then((data) => {
          cookie.setCookie('auth_token', data.auth_token);
          this.$router.push({ name: 'User', params: { userId: data.user_id } });
        });
      },
      () => {
        this.error = 'Please check Netowrk Connection and try again!';
      })
      .then(() => { this.loggingIn = false; });
    },
  },
};
</script>

<style lang="scss" scoped>
.ev-login {
  margin-top: 50px;
}
</style>

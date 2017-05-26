<template>
  <div class="ev-login col-sm-4 offset-sm-4">
    <div>User Profile - User Id: {{userId}}</div>
    <AddImage @save="addImage"> </AddImage>
    <spinner v-show="loading" message="Loading..."></spinner>
    <div class="alert alert-danger" v-if="error">
      <p>{{ error }}</p>
    </div>

    <div v-for="imageData in images">
      <Beimage :image="imageData"></Beimage>
    </div>
 </div>
</template>

<script>
import Spinner from '@/components/common/Spinner';
import Beimage from '@/components/Beimage';
import AddImage from '@/components/AddImage';
import services from '@/api/services';

export default {
  name: 'user',
  props: ['userId'],
  components: { Spinner, Beimage, AddImage },
  data() {
    return {
      loading: false,
      post: null,
      error: null,
      images: [],
    };
  },

  created() {
    // fetch the data when the view is created and the data is
    // already being observed
    this.fetchData();
  },

  watch: {
    // call again the method if the route changes
    $route: 'fetchData',
  },

  methods: {
    addImage(image) {
      services.addImage(this.userId, image)
      .then(
        (response) => {
          if (response.status !== 201) {
            this.error = 'Not authorized to add image to this user';
            return;
          }
          response.json().then((data) => {
            this.images.push(data);
          });
        },
        () => {
          this.error = 'Please check Network Connection and try again!';
        })
      .then(() => { this.loading = false; });
    },

    fetchData() {
      this.error = null;
      this.images = null;
      this.loading = true;

      // replace getPost with your data fetching util / API wrapper
      services.getAllImages(this.userId)
      .then(
      (response) => {
        if (response.status !== 200) {
          this.error = 'Not authorized to access user\'s images';
          return;
        }
        response.json().then((data) => {
          this.images = data;
        });
      },
      () => {
        this.error = 'Please check Netowrk Connection and try again!';
      })
      .then(() => { this.loading = false; });
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}

.btn-add-image {
  margin: 10px 0 10px;
}
</style>

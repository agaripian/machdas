<template>
  <div class="ev-login">
    <div class="form-group">
      <input
              type="text"
              class="form-control js-image-name"
              placeholder="Image Name"
              v-model="image.name"
      >
      <input
              type="text"
              class="form-control js-image-description"
              placeholder="Image Description"
              v-model="image.description"
      >
      <input
              type="text"
              class="form-control js-image-url"
              placeholder="Image Url"
              v-model="image.url"
      >
    </div>
    <button
            class="btn btn-primary solid blank js-login__submit"
            @click="submit()" :disabled="valid"
    >
      Add Image
    </button>
 </div>
</template>

<script>

export default {
  name: 'addimage',

  data() {
    return {
      image: {
        name: '',
        description: '',
        url: '',
      },
    };
  },

  computed: {
    valid() {
      return !this.image.name || !this.image.description || !this.image.url;
    },
  },

  methods: {
    submit() {
      this.$emit('save', this.image);
      this.image.imageUrl = '';
      this.image.imageName = '';
      this.image.imageDescription = '';
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}
</style>

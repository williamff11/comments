<template>
  <form class="form" @submit.prevent="onSubmit">
    <div class="form-group">
      <textarea
        @click.prevent="showButton"
        class="form-control"
        id="exampleFormControlTextarea1"
        rows="2"
        placeholder="Adicionar Comentários públicos"
        v-model="comment.messageComment"
      ></textarea>
    </div>
    <div v-if="viewButton" class="form-group" style="float: right;">
      <button @click.prevent="noViewButton" type="button" class="btn btn-secondary">Cancelar</button>

      <button type="submit" class="btn btn-success">Comentar</button>
    </div>
  </form>
</template>
<script>
export default {
  props: {
    folder_id: {
      required: true
    },
    comment_id: {
      required: false,
      default: ''
    }
  },
  data() {
    return {
      viewButton: false,
      comment: {
        messageComment: "",
        folder_id: this.folder_id,
        comment_id: this.comment_id
      },
      errors: {}
    };
  },
  methods: {
    onSubmit() {
      const formData = new FormData();

      formData.append("messageComment", this.comment.messageComment);
      formData.append("folder_id", this.folder_id);
      formData.append("comment_id", this.comment_id);

      this.$store
        .dispatch("storeComment", formData)
        .then(() => {
          this.noViewButton();
          this.$snotify.success("Sucesso!");
          this.errors = {};
          this.$emit("success");
        })
        .catch(errors => {
          this.errors = errors.data.errors;
          this.$snotify.error(this.errors.messageComment[0], "Error");
        });
    },
    showButton() {
      this.viewButton = true;
    },
    noViewButton() {
      this.viewButton = false;
      this.comment = {
        messageComment: ""
      };
    }
  }
};
</script>

<style scoped>
</style>
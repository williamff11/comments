<template>
  <div id="demo">
    <br />
    <div class="card border col-sm-12 shadow">
      <div class="card-header linha">
        <h3 class="card-title col">{{folder.name}}</h3>

        <button
          v-if="!addFiles"
          type="submit"
          class="btn btn-success btn-sm"
          @click.prevent="addFiles = true"
          data-toggle="tooltip"
          data-placement="top"
          title="Upar aquivos"
        >
          <i class="fas fa-plus fa-2x"></i>
        </button>
        <uploadFiles v-if="addFiles" @sendFile="sendFile" />
      </div>
      <div class="card-body">
        <table class="table table-hover">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Nome</th>
              <th scope="col">Responsável</th>
              <th scope="col">Última Modificação</th>
              <th scope="col">Tamanho</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(document, index) in documents.data"
              :key="index"
              id="clickme"
              @click.prevent="downloadDocument(document.id, document.name)"
              @contextmenu="openMenu($event, document)"
            >
              <th>{{document.name}}</th>
              <td>{{document.user.nomeCompleto}}</td>
              <td>{{ document.updated_at }}</td>
              <td>{{ document.size }} KB</td>
            </tr>
          </tbody>
        </table>
        <ul
          id="right-click-menu"
          tabindex="-1"
          ref="right"
          v-if="viewMenu"
          @blur="closeMenu"
          :style="{top:top, left:left}"
        >
          <li @click.prevent="confirmDestroy">
            <i class="fas fa-trash"></i>
            Deletar
          </li>
        </ul>
        <pagination :pagination="documents" :offset="documents.last_page" @paginate="loadDocuments"></pagination>
      </div>
    </div>
    <br />
    <commentsComponent :folder_id='id'></commentsComponent>
    <!-- <listagemComponent :documents="documents"></listagemComponent> -->
  </div>
</template>

<script>
// import Vodal from "vodal";
import { mapState } from "vuex";

import UploadFiles from "../../../../layouts/UploadFiles";
import PaginationComponent from "../../../../layouts/PaginationComponent";
import CommentsComponent from "../../../../layouts/comments/CommentsComponent";
import ListagemComponent from "../../../../layouts/partialsFiles/ListagemDocuments";
export default {
  props: {
    id: {
      required: true
    },
    name: {
      required: true
    }
  },
  data() {
    return {
      showModal: false,
      filter: "",
      folder: {},
      files: [],
      addFiles: false,
      viewMenu: false,
      top: "0px",
      left: "0px",
      document: {}
    };
  },
  created() {
    this.loadDocuments(1), this.loadFolder(this.id);
  },
  computed: {
    ...mapState({
      documents: state => state.documents.items
    }),
    params() {
      return {
        page: this.documents.current_page,
        folder_id: this.id
      };
    }
  },
  methods: {
    downloadDocument(id, name) {
      this.$store
        .dispatch("downloadDocument", {
          id: id,
          nameFile: name
        })
        .then(() => {
          this.$snotify.success("Sucesso!");
          this.errors = {};
        })
        .catch(errors => {
          this.$snotify.error("ERROR", "Error");
        });
    },
    loadDocuments(page) {
      this.$store.dispatch("loadDocuments", { ...this.params, page });
    },
    loadFolder(id) {
      this.$store
        .dispatch("loadFolder", id)
        .then(response => {
          this.folder = response;
        })
        .catch(errors => {
          this.$snotify.error("Pasta não Encontrada", "404");
          this.$router.push({ name: "admin.files" });
        });
    },
    sendFile(files) {
      this.files = files;
      this.submitFiles();
    },
    submitFiles() {
      for (let i = 0; i < this.files.length; i++) {
        if (this.files[i].id) {
          continue;
        }
        let formData = new FormData();
        formData.append("file", this.files[i]);
        formData.append("folder_id", this.id);

        this.$store
          .dispatch("uploadDocuments", formData)
          .then(() => {
            this.loadDocuments(this.id);
            this.$snotify.success("Sucesso!");
            this.addFiles = false;
            this.files[i].id = data["data"]["id"];
            this.files.splice(i, 1, this.files[i]);
          })
          .catch(error => {
            console.log(error.response.data.errors.name);
            this.errors = error.response.data.errors;
            this.$snotify.error(this.errors.name[0], "Error");
          });
      }
    },
    confirmDestroy() {
      this.$snotify.warning(
        `Deseja realmente deletar este arquivo: ${this.document.name}?`,
        "Tem Certeza?",
        {
          timout: 10000,
          showProgressBar: true,
          closeOnClick: true,
          buttons: [
            {
              text: "Não",
              action: value => {
                this.$snotify.remove(value.id);
              }
            },
            {
              text: "Sim",
              action: value => {
                this.deletePa(this.document), this.$snotify.remove(value.id);
              },
              bold: false
            }
          ]
        }
      );
    },
    deletePa(document) {
      this.$store
        .dispatch("deleteDocument", document.id)
        .then(() => {
          this.$snotify.success(
            `Deletado com sucesso o arquivo: ${document.name} `
          );
          this.loadDocuments(1);
        })
        .catch(error => {
          this.$snotify.error("Error", "Error");
        });
    },
    setMenu(top, left) {
      var largestHeight =
        window.innerHeight - this.$refs.right.offsetHeight - 25;
      var largestWidth = window.innerWidth - this.$refs.right.offsetWidth - 25;

      if (top > largestHeight) top = largestHeight;

      if (left > largestWidth) left = largestWidth;

      this.top = top + "px";
      this.left = left + "px";
    },

    closeMenu() {
      this.viewMenu = false;
    },

    openMenu(event, document) {
      console.log(event);
      this.document = document;
      this.viewMenu = true;

      Vue.nextTick(
        function() {
          // DOM updated
          this.$refs.right.focus();

          this.setMenu(event.pageY, event.pageX);
        }.bind(this)
      );

      event.preventDefault();
    }
  },
  components: {
    UploadFiles,
    CommentsComponent,
    pagination: PaginationComponent,
    listagemComponent: ListagemComponent
  }
};
</script>

<style scoped>
#demo {
  width: 100%;
  height: 100%;
}

#right-click-menu {
  background: #fafafa;
  border: 1px solid #bdbdbd;
  box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.2),
    0 1px 5px 0 rgba(0, 0, 0, 0.12);
  display: block;
  list-style: none;
  margin: 0;
  padding: 0;
  position: absolute;
  width: 250px;
  z-index: 999999;
}

#right-click-menu li {
  border-bottom: 1px solid #e0e0e0;
  margin: 0;
  padding: 5px 35px;
}

#right-click-menu li:last-child {
  border-bottom: none;
}

#right-click-menu li:hover {
  background: #1e88e5;
  color: #fafafa;
}
</style>
<template>
  <div>
    <div class="container-fluid text-light bg-dark ms-auto my-5 p-5">
      <h1>Regisztrációk</h1>
      <div class="d-flex justify-content-end text-light align-items-center my-3">
        <h5 class="mx-3">Rendezés:</h5>
        <select
          v-model="orderBy"
          @change="getRegistrations"
          class="form-select"
          style="width: auto"
          aria-label="Default select example"
        >
          <option value="id">Azonosító (ID)</option>
          <option value="user_id">Felhasználó</option>
          <option value="event_id">Esemény (ID)</option>
          <option value="headcount">Létszám</option>
        </select>
         <ordering v-model="ordering" @click.native="getRegistrations"> </ordering>
      </div>
      <table class="table table-striped table-light table-hover">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Felhasználó</th>
            <th scope="col">Esemény (ID)</th>
            <th scope="col">Létszám</th>
            <th scope="col">Funkciók</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(registration, r) in registrations" :key="r">
            <th>{{ registration.id }}</th>
            <td>{{ registration.user.email }}</td>
            <td>{{ registration.event.name}} ({{ registration.event.id}})</td>
            <td>{{ registration.headcount}}</td>
            <td>
              <div class="d-flex justify-content-start">
                <i
                  class="bi bi-pencil-fill edit-icon mx-2"
                  title="Szerkesztés"
                  @click="showEditModal(registration, r)"
                ></i>
                <i
                  class="bi bi-trash3-fill delete-icon mx-2"
                  title="Törlés"
                  @click="showDeleteModal(registration.id, r)"
                ></i>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- Pagination -->
      <b-pagination
        v-model="currentPage"
        :total-rows="total"
        :per-page="itemPerPage"
        align="center"
        @change="handlePageChange"
      ></b-pagination>
      <!-- Pagination -->
    </div>
    <!-- Edit Modal -->
    <b-modal
      v-model="editModal"
      title="Regisztráció szerkesztése: "
      hide-header-close
      hide-footer
      no-close-on-esc
      no-close-on-backdrop
    >
      <div class="mb-3">
        <label class="form-label">Létszám: </label>
        <input class="form-control" min="1" type="number" v-model="editData.headcount" />
      </div>
      <div class="d-flex justify-content-end">
        <button
          type="button"
          class="btn btn-danger mx-2"
          @click="editModal = false"
        >
          Bezárás
        </button>
        <button
          type="button"
          :disabled="editing"
          @click="edit"
          class="btn btn-success mx-2"
        >
          <span
            class="spinner-border spinner-border-sm"
            role="status"
            aria-hidden="true"
            v-show="editing"
          ></span>
          <span class="sr-only">Mentés</span>
        </button>
      </div>
    </b-modal>
    <!-- Edit Modal -->
      <deleteModal> </deleteModal>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import deleteModal from './deleteModal.vue';
import ordering from "./ordering.vue";

export default {
  components: { deleteModal, ordering },
  props: ['getUrl'],
  data() {
    return {
      registrations: [],
      addModal: false,
      adding: false,
      orderBy: "id",
      ordering: 'desc',

      //pagination
      itemPerPage: 10,
      currentPage: 1,
      total: 0,

      editModal: false,
      editData: {},
      editing: false,
      editIndex: "",
    };
  },
  methods: {
    handlePageChange(value) {
      this.currentPage = value;
      this.getRegistrations();
    },
    showDeleteModal(id, index) {
      const deleteModalObj = {
        showDeleteModal: true,
        deleteUrl: "/app/delete_registration",
        data: { id: id },
        deletingIndex: index,
        msg: "Biztosan törölni akarja ezt a regisztrációt?",
        successMsg: "Regisztráció sikeresen törölve!",
      };
      this.$store.commit("setDeleteModalObj", deleteModalObj);
    },
    showEditModal(registration, index) {
      const obj = {
        id: registration.id,
        headcount: registration.headcount,
      };
      this.editData = obj;
      this.editIndex = index;
      this.editModal = true;
    },
    async edit() {
       if (this.editData.headcount.length == 0)
        return this.$toast.warning("Létszám megadása kötelező!");
        if (this.editData.headcount > 5)
        return this.$toast.warning("Legfejebb öt fővel regisztrálhat!");

      this.editing = true;

      const res = await this.callApi("post", "/app/edit_registration", this.editData);

      if (res.status == 200) {
        this.registrations[this.editIndex].headcount = this.editData.headcount;
        this.$toast.success("Regisztráció sikeresen szerkesztve!");
      } else {
        this.$toast.error("Regisztráció szerkesztése sikertelen!");
      }

      this.editing = false;
      this.editModal = false;
    },
    async getRegistrations() {
      const res = await this.callApi(
        "get",
        `${this.getUrl}?page=${this.currentPage}&itemPerPage=${this.itemPerPage}&orderBy=${this.orderBy}&ordering=${this.ordering}`
      );
      if (res.status == 200) {
        this.registrations = res.data.data;
        this.currentPage = res.data.current_page;
        this.total = res.data.total;
      } else {
        this.$toast.error("Regisztráció betöltése sikertelen!");
      }
    },
  },
  computed: {
    ...mapGetters(["getUser",  "getDeleteModalObj"]),
  },
  created() {
    this.getRegistrations();
  },
   watch: {
    getDeleteModalObj(obj) {
      if (obj.isDeleted) {
        this.registrations.splice(obj.deletingIndex, 1);
      }
    },
  },
};
</script>
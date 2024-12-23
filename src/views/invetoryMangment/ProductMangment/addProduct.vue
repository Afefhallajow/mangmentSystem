<template>
  <div class="container mt-2">
    <h1>{{ isEdit ? 'Edit Product' : 'Add Product' }}</h1>
    <form @submit.prevent="handleSubmit">
      <div class="form-group mb-2">
        <label for="name">Product Name</label>
        <input type="text" id="name" v-model="item.name" class="form-control" required/>
      </div>
      <div class="form-group mb-2">
        <label for="description">Description</label>
        <input type="text" id="description" v-model="item.description" class="form-control" required/>
      </div>
      <div class="form-group mb-2">
        <label for="quantity">Quantity</label>
        <input type="number" id="quantity" v-model="item.quantity" class="form-control" required/>
      </div>
      <div class="form-group mb-2">
        <label for="price">Price</label>
        <input type="number" id="price" v-model.number="item.price" min="0" step="any" class="form-control" required/>
      </div>
      <div class="row mb-2 " v-if="isEdit">
        <div class="col-md-12 d-flex " style="overflow:hidden;">
          <!-- Thumbnail Images (Vertical list) -->
          <div v-for="(attachment, index) in item.attachments" :key="index" class="col-3 mb-3">
            <img
                :src="'http://localhost:8000' + attachment.path"
                alt="Thumbnail Image"
                class="img-fluid"
                style="cursor: pointer; width: 100%;height: 156px;"
                @click="openModal(attachment.path)"/>
            <button type="button" class="btn btn-danger mt-2" @click="deleteFile(attachment.id, index, attachment.path)">delete File</button>
          </div>
        </div>
      </div>
      <div class="form-group mb-2 ">
        <label for="file-upload">Attachments</label>
        <br>
        <input type="file" id="file-upload" multiple @change="handleAttachments" />
        <button type="button" class="btn btn-secondary" @click="uploadFiles">{{isEdit ? "upload new files" : "upload files"}}</button>
      </div>

      <!-- Progress bar for file upload -->
      <div v-if="uploadProgress > 0" class="progress mb-2">
        <div class="progress-bar" role="progressbar" :style="{ width: uploadProgress + '%' }" aria-valuenow="uploadProgress" aria-valuemin="0" aria-valuemax="100">
          {{ uploadProgress }}%
        </div>
      </div>

      <!-- Show submit button only if file URLs are set -->
      <button type="submit" class="btn btn-primary" :disabled="!isEdit && (item.file_urls.length === 0 || uploadProgress < 100)">
        {{ isEdit ? 'Update' : 'Add' }}
      </button>
    </form>
  </div>
  <div
      class="modal fade"
      id="imageModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="imageModalLabel"
      aria-hidden="false"
  >
    <div class="modal-dialog modal-fullscreen" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <img :src="'http://localhost:8000' + selectedImage"
               alt="Preview"
               class="img-fluid"
          />
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import axios from 'axios';
import {Modal} from "bootstrap";

export default {
  name: "addProduct",
  data() {
    return {
      selectedImage: String,
      item: {
        id: '',
        name: '',
        quantity: '',
        price: '',
        file_urls: [],  // To store URLs of uploaded files
      },
      attachments: [],  // To hold selected files before upload
      isEdit: false,
      uploadProgress: 0,  // To track the upload progress
      unsavedChanges: false,
    };
  },
  created() {
/*
    window.addEventListener('beforeunload', () => {

      this.removeUser()

      return null
    });
*/
    const projectId = this.$route.params.id;
    if (projectId) {
      this.isEdit = true;
      this.fetchProduct(projectId);
    }
  },
  mounted() {
    window.addEventListener("beforeunload",  (event) => {
      console.log(this.unsavedChanges)
      if (this.unsavedChanges) {
        event.preventDefault();
        event.returnValue = "";
      }
    })
  },
  methods: {
    openModal(path){
      this.selectedImage = path;
      const modal = new Modal(document.getElementById('imageModal'));
      modal.show();
    },
    //Method to handle file selection
    handleAttachments(event) {
      this.attachments = Array.from(event.target.files); // Convert FileList to Array
    },
    async deleteFile(id, index, path){
      if(id == null) {
        console.log("no id ");
        axios.post(`/delete`, {"path":path}).then((response)=>{
          this.item.attachments.splice(index, 1);
          this.item.file_urls = this.item.file_urls.filter(mainpath => path !== mainpath);
          if(this.item.file_urls.length === 0)
            this.unsavedChanges = false;
          console.log(this.unsavedChanges)
          alert(response.data)
        }).catch(reason => alert(reason))
        return;
      }
      axios.delete(`/delete/${id}`).then((response)=>{
        this.item.attachments.splice(index, 1);
        alert(response.data)
      }).catch(reason => alert(reason))
    },
    // Method to upload files to the Gateway
    async uploadFiles() {
      const formData = new FormData();
      // Append each file to formData
      this.attachments.forEach(file => {
        formData.append('attachments[]', file);
      });
      try {
        // Send the form data to the Gateway API for file upload with progress tracking
        const response = await axios.post('upload-attachments', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
          onUploadProgress: (progressEvent) => {
            if (progressEvent.lengthComputable) {
              this.uploadProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
            }
          }
        });

        // Store the URLs of the uploaded files
        this.item.file_urls = response.data.file_urls;
        this.item.attachments = this.item.attachments || []; // Initialize if undefined
        this.unsavedChanges = true;
        console.log('File URLs:', this.item.file_urls);
        console.log('unSaved:', this.unsavedChanges);
        this.item.file_urls.forEach((path) =>{
          this.item.attachments.push({"path":path})
        })
      } catch (error) {
        console.error('Error uploading files:', error);
        alert('Error uploading files');
      }
    },

    // Method to fetch product data for editing
    async fetchProduct(id) {
      try {
        const response = await axios.get(`inventory/product/getOne/${id}`);
        this.item = response.data.data;
        this.item.file_urls = this.item.file_urls || [];
      } catch (error) {
        console.error('Error fetching product:', error);
      }
    },

    // Method to handle form submission (Add or Update)
    async handleSubmit() {
      try {
        if (this.isEdit) {
          // Update existing product
          await axios.post(`inventory/product/update`, this.item);
          alert('Product updated successfully');
        } else {
          // Add new product
          await axios.post('inventory/product/save', this.item);
          alert('Product added successfully');
        }
        this.unsavedChanges = false
        if(!this.isEdit)
        this.$router.push({ name: 'products' });
      } catch (error) {
        console.error('Error saving product:', error);
        alert('Error saving product');
      }
    },
  },
};
</script>
<style scoped>
</style>

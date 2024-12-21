<template>
  <div class="container mt-2">
    <h1>{{ isEdit ? 'Edit Product' : 'Add Product' }}</h1>
    <form @submit.prevent="handleSubmit">
      <div class="form-group mb-2">
        <label for="name">Product Name</label>
        <input type="text" id="name" v-model="item.name" class="form-control" required/>
      </div>
      <div class="form-group mb-2">
        <label for="quantity">Quantity</label>
        <input type="number" id="quantity" v-model="item.quantity" class="form-control" required/>
      </div>
      <div class="form-group mb-2">
        <label for="price">Price</label>
        <input type="number" id="price" v-model.number="item.price" min="0" step="any" class="form-control" required/>
      </div>

      <div class="form-group mb-2">
        <label for="file-upload">Attachments</label>
        <input type="file" id="file-upload" multiple @change="handleAttachments" />
        <button type="button" class="btn btn-secondary" @click="uploadFiles">Upload Files</button>
      </div>

      <!-- Progress bar for file upload -->
      <div v-if="uploadProgress > 0" class="progress mb-2">
        <div class="progress-bar" role="progressbar" :style="{ width: uploadProgress + '%' }" aria-valuenow="uploadProgress" aria-valuemin="0" aria-valuemax="100">
          {{ uploadProgress }}%
        </div>
      </div>

      <!-- Show submit button only if file URLs are set -->
      <button type="submit" class="btn btn-primary" :disabled="item.file_urls.length === 0 || uploadProgress < 100">
        {{ isEdit ? 'Update' : 'Add' }}
      </button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: "addProduct",
  data() {
    return {
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
    };
  },
  created() {
    const projectId = this.$route.params.id;
    if (projectId) {
      this.isEdit = true;
      this.fetchProduct(projectId);
    }
  },
  methods: {
    //Method to handle file selection
    handleAttachments(event) {
      this.attachments = Array.from(event.target.files); // Convert FileList to Array
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
        console.log('File URLs:', this.item.file_urls);
      } catch (error) {
        console.error('Error uploading files:', error);
        alert('Error uploading files');
      }
    },

    // Method to fetch product data for editing
    async fetchProduct(id) {
      try {
        const response = await axios.get(`inventory/product/getOne/${id}`);
        this.item = response.data;
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

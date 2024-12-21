<template>
  <div class="sidebar-wrapper" @mouseover="activateSidebar" @mouseleave="deactivateSidebar">
    <div class="sidebar" v-show="isSidebarActive" >
      <div class="input-group mb-3 sidebar-search" style="max-width: 300px;">
    <span class="input-group-text bg-light" id="search-icon">
      <i class="bi bi-search"></i> <!-- Bootstrap Icon -->
    </span>
        <input
            type="text"
            class="form-control"
            placeholder="Search"
            aria-label="Search"
            aria-describedby="search-icon"
            v-model="searchQuery"
            @input="performSearch"
        />
      </div>
      <h2>Sidebar</h2>
      <ul class="sidebar-ul">
        <li v-for="(item, index) in sidebarItems" :key="index" class="sidebar-items">
          <a   class="sidebar-items-title" @click="toggleExpand(index)">
            {{ item.title }}
            <span :class="isExpanded(index) ? 'bi bi-chevron-down' : 'bi bi-chevron-right'"></span>
          </a>
          <!-- Dropdown list that expands on click -->
          <ul v-show="isExpanded(index)" class="sidebar-inner-ul">
            <li
                v-for="(subItem, subIndex) in item.subItems"
                :key="subIndex"
                class="sidebar-items"
            >
              <a class="sidebar-items-title" @click="toggleExpandInnerIndex(subIndex)">
                {{ subItem.title }}
                <span :class="isExpandedInnerIndex(subIndex) ? 'bi bi-chevron-down' : 'bi bi-chevron-right'"></span>
              </a>
              <!-- Dropdown list that expands on click -->
              <ul v-show="isExpandedInnerIndex(subIndex)" class="sidebar-inner-items-ul">
                <li v-for="(innerItem, innerIndex) in subItem.items"
                    :key="innerIndex"
                    class="sidebar-inner-items"
                >
                  <router-link v-if="innerItem.route" :to="innerItem.route" class="sidebar-routes">
                    {{ innerItem.name }}
                  </router-link>
                  <span v-else>{{ innerItem.name }}</span>
                </li>
              </ul>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  name: "SideBar",
  data() {
    return {
      searchQuery: null,
      isSidebarActive: false,
      expandedIndex: null,
      expandedInnerIndex: null,
      sidebarItems: [{
        title: 'Projects Management',
        subItems: [
          {
            title: 'Projects',
            items: [
              { name: 'Add Project', route: '/project/add' },
              { name: 'View Project', route: '/project' },
            ],
          },
          {
            title: 'Tasks',
            items: [
              { name: 'Add Task', route: '/task/add' },
              { name: 'View Task', route: '/task' },
            ],
          }
        ]
      },
      {
        title: 'Hr Management',
        subItems: [
      {
        title: 'Attendance',
        items: [
          { name: 'Add Attendance', route: '/attendance/save' },
        ],
      },
      {
        title: 'Employee',
        items: [
          { name: 'Add Employee', route: '/employee/save' },
          { name: 'view Employee', route: '/employee/getAll' },
        ],
      },
    ]
  },
        {
          title: 'Inventory Management',
          subItems: [
          {
            title: 'Products',
            items: [
              { name: 'Products', route: '/Inventory/products' },
              { name: 'Add Product', route: '/Inventory/products/save' },
            ],
          },
        ]
      },



        /*     {
        title: 'Projects',
        subItems: [
          { name: 'Add Project', route: '/project/add' },
          { name: 'View Project', route: '/project' },
        ],
      },
      {
        title: 'Tasks',
        subItems: [
          { name: 'Add Task', route: '/task/add' },
          { name: 'View Task', route: '/task' },
        ],
      },
 */],
    };
  },
  methods: {
    activateSidebar() {
      this.isSidebarActive = true;
    },
    deactivateSidebar() {
      // Hide sidebar only if searchQuery is empty
      if (!this.searchQuery) {
        this.isSidebarActive = false;
      }
    },
    toggleExpand(index) {
      this.expandedIndex = this.expandedIndex === index ? null : index;
    },
    isExpanded(index) {
      return this.expandedIndex === index;
    },
    toggleExpandInnerIndex(index) {
      this.expandedInnerIndex = this.expandedInnerIndex === index ? null : index;
    },
    isExpandedInnerIndex(index) {
      return this.expandedInnerIndex === index;
    },
  },
}
</script>

<style scoped>

</style>
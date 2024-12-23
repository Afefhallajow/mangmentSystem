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
      <ul v-if="getUser().type === 'employee'" class="sidebar-ul">
        <li v-for="(item, index) in filteredSidebarItems" :key="index" class="sidebar-items">
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
      <ul class="sidebar-ul" v-if="getUser().type === 'client'">
        <li v-for="(items, index) in sidebarItemsClients" :key="index" class="sidebar-items">
          <router-link v-if="items.route" :to="items.route" class="sidebar-routes">
            {{ items.title }}
          </router-link>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";

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
              { name: 'Add Project', route: '/project/add', permission: 'create projects'},
              { name: 'View Project', route: '/project' , permission: 'view projects'},
            ],
          },
          {
            title: 'Tasks',
            items: [
              { name: 'Add Task', route: '/task/add' , permission: 'create task'},
              { name: 'View Task', route: '/task',  permission: 'view task'},
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
          { name: 'Add Attendance', route: '/attendance/save', permission: 'create attendance'},
        ],
      },
      {
        title: 'Employee',
        items: [
          { name: 'Add Employee', route: '/employee/save', permission: 'create employees' },
          { name: 'view Employee', route: '/employee/getAll', permission: 'view employees' },
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
              { name: 'Products', route: '/Inventory/products', permission: 'view products' },
              { name: 'Add Product', route: '/Inventory/products/save', permission: 'create products' },
            ],
          },
        ]
      }],
      sidebarItemsClients:[
        {
            title : "My Products",
            route:'/Inventory/MyProduct'
        },{
            title : "market",
            route:'/market'
        },
      ]

    };
  },
  computed:{
    ...mapGetters(['hasPermission', "getUser"]),
    filteredSidebarItems() {
      return this.sidebarItems.map((item) => {
          // Filter subItems based on permissions
          const filteredSubItems = item.subItems.map((subItem) => {
                const filteredItems = subItem.items.filter((innerItem) =>
                    this.hasPermission(innerItem.permission)
                );
                // Return subItem only if it has at least one accessible innerItem
                return filteredItems.length > 0 ? { ...subItem, items: filteredItems } : null;
              })
              .filter((subItem) => subItem !== null); // Remove null subItems

          // Return item only if it has at least one accessible subItem
          return filteredSubItems.length > 0 ? { ...item, subItems: filteredSubItems } : null;
        })
            .filter((item) => item !== null); // Remove null items
      }
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
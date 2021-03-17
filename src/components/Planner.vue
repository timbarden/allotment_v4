<template>
  <div>
    <div class="planner">
      <header class="planner__header">
        <h1>Allotment Diary</h1>
        <button
          v-if="$auth.isAuthenticated"
          class="btn--sq"
          @click="itemShow()"
        >
          <img src="../assets/create-outline.svg" alt="Create" />
        </button>
      </header>

      <ul class="planner__list" v-if="$auth.isAuthenticated">
        <GrowItem
          v-for="item in sortList"
          :key="item.id"
          :name="item.name"
          :entries="item.entries"
          :style="{ 'border-color': item.color }"
          @itemShow="itemShow(item.id)"
          @itemDelete="itemDelete(item.id)"
        />
      </ul>

      <div class="planner__welcome" v-if="!$auth.isAuthenticated">
          <p>Welcome! You need to log in.</p>
      </div>
    </div>

    <transition name="fade">
      <div class="item" v-if="$auth.isAuthenticated && blnItemOpen">
        <div class="item__box" :class="{ entryactive: blnEntryOpen }">
          <div class="item__box__home">
            <form
              class="item__box__home__form"
              action=""
              :style="{ 'border-color': activeItem.color }"
              @submit.prevent
            >
              <div class="item__box__home__form__header">
                <input
                  class="item__box__home__form__header__name"
                  type="text"
                  name="name"
                  v-model="activeItem['name']"
                  placeholder="Item name"
                />
                <input type="color" name="color" v-model="activeItem['color']" />
              </div>
              <div class="item__box__home__form__main">
                <select name="type" v-model="activeItem['type']">
                  <option value="" disabled>Type:</option>
                  <option
                    v-for="(name, index) in arrGroups"
                    :key="index"
                    :value="name"
                    >{{ name }}</option
                  >
                </select>
              </div>
            </form>
            <div class="item__box__home__main">
              <div class="item__entries">
                <header class="item__entries__header">
                  <h3>Entries</h3>
                  <button @click="entryShow()">Add entry</button>
                </header>
                <ul class="item__entries__list">
                  <li v-for="entry in sortEntries" :key="entry.id">
                    <div>
                      <span
                        >{{ formatDate(entry.sowdate) }}
                        <span v-if="entry.harvestdate">-</span>
                        {{ formatDate(entry.harvestdate) }}</span
                      >
                      <span v-if="entry.sowdate && entry.harvestdate">{{
                        dateDifference(entry.sowdate, entry.harvestdate)
                      }}</span>
                      <p>{{ entry.notes }}</p>
                    </div>
                    <div class="item__entries__list__btns">
                      <button class="btn--sq" @click="entryShow(entry.id)">
                        <img src="../assets/pencil-outline.svg" alt="Edit" />
                      </button>
                      <button class="btn--sq" @click="entryDelete(entry.id)">
                        <img src="../assets/trash-outline.svg" alt="Delete" />
                      </button>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="item__btns">
              <button class="item__btn" @click="itemClose">Cancel</button>
              <button @click="itemSave(blnItemNew)">Save</button>
            </div>
          </div>

          <div v-if="blnEntryOpen" class="item__box__home__entry entry">
            <form class="entry__form" action="">
              <label class="for">Notes / results</label>
              <textarea
                name="notes"
                cols="30"
                rows="6"
                class="entry__form__notes"
                v-model="activeEntry['notes']"
              ></textarea>
              <label>Varieties</label>
              <input
                type="text"
                name="varieties"
                v-model="activeEntry['varieties']"
              />
              <div class="entry__form__dates">
                <div>
                  <label>Sown</label>
                  <input
                    type="date"
                    name="sowdate"
                    v-model="activeEntry['sowdate']"
                  />
                </div>
                <div>
                  <label>Direct?</label>
                  <input
                    type="checkbox"
                    name="sowtype"
                    v-model="activeEntry['sowtype']"
                  />
                </div>
                <div>
                  <label v-if="!sownDirect">Planted out</label>
                  <input
                    v-if="!sownDirect"
                    type="date"
                    name="plantdate"
                    v-model="activeEntry['plantdate']"
                  />
                </div>
                <div>
                  <label>First harvest</label>
                  <input
                    type="date"
                    name="harvestdate"
                    v-model="activeEntry['harvestdate']"
                  />
                </div>
                <div>
                  <fieldset name="success">
                    <legend>Success?</legend>
                    <div>
                      <input type="radio" name="success" value="success_yes">
                      <label>Yes</label>
                    </div>
                    <div>
                      <input type="radio" name="success" value="success_no">
                      <label>No</label>
                    </div>
                  </fieldset>
                </div>
              </div>
            </form>
            <div class="entry__btns">
              <button @click="entryClose" class="entry__btn">Cancel</button>
              <button @click="entrySave(blnEntryNew)" class="entry__btn">
                Save
              </button>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import GrowItem from "../components/GrowItem.vue";

export default {
  data() {
    return {
      arrMonths: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
      blnItemOpen: false,
      blnItemNew: false,
      blnEntryOpen: false,
      blnEntryNew: false,
      growList: [],
      activeItem: {},
      activeEntry: {},
      activeEntries: [],
      intLastId: 0,
      objGroups: {},
      arrGroups: ["Vegetables", "Flowers", "Fruit"]
      //publicPath: process.env.BASE_URL,
    };
  },
  components: {
    GrowItem,
  },
  methods: {
    groupTitle: function(type) {
      return this.objGroups[type].length;
    },
    itemShow: function(id) {
      this.blnItemOpen = true;
      if (id == undefined) {
        // new item
        this.blnItemNew = true;
        this.activeItem = {};
        this.activeItem.type = "";
      } else {
        // existing item - transfer data to activeItem and make observable
        let objItem = this.listItemMatch(id, this.growList)[0];
        this.activeItem = JSON.parse(JSON.stringify(objItem));
        this.activeEntries = JSON.parse(JSON.stringify(objItem.entries));
        this.activeItem.type == undefined && (this.activeItem.type = "");
      }
    },
    itemClose: function() {
      this.blnItemOpen = false;
      this.blnItemNew = false;
      this.activeItem = {};
      this.activeEntries = [];
    },
    itemSave: function(blnNewItem) {
      let objItem = {};
      if (blnNewItem) {
        // assign ID to new item
        this.intLastId++;
        objItem.id = this.intLastId;
      } else {
        // retrieve existing item
        objItem = this.listItemMatch(this.activeItem.id, this.growList)[0];
      }
      let arrFields = document.querySelectorAll(
        ".item__box__home__form input, .item__box__home__form textarea, .item__box__home__form select"
      );
      arrFields.forEach((field) => {
        objItem[field.name] = this.activeItem[field.name];
      });
      objItem.entries = this.activeEntries;
      // add new item to growList, save and close
      blnNewItem && this.growList.push(objItem);
      this.saveData(process.env.VUE_APP_DATA);
      this.itemClose();
    },
    itemDelete: function(id) {
      if (confirm("Are you sure?")) {
        let thisItem = this.listItemMatch(id, this.growList)[1];
        this.growList.splice(thisItem, 1);
        this.saveData(process.env.VUE_APP_DATA);
      }
    },
    entryShow: function(id) {
      this.blnEntryOpen = true;
      if (id == undefined) {
        this.blnEntryNew = true;
        this.activeEntry = {};
      } else {
        let objItem = this.listItemMatch(id, this.activeEntries)[0];
        this.activeEntry = JSON.parse(JSON.stringify(objItem));
      }
    },
    entryClose: function() {
      this.blnEntryOpen = false;
      this.blnEntryNew = false;
      this.activeEntry = {};
    },
    entrySave: function(blnNewEntry) {
      let objItem = {};
      if (blnNewEntry) {
        // assign ID to new entry
        this.intLastId++;
        objItem.id = this.intLastId;
      } else {
        // retrieve existing entry
        objItem = this.listItemMatch(
          this.activeEntry.id,
          this.activeEntries
        )[0];
      }
      //console.log("fieldset", document.querySelector(".entry__form fieldset input").value)
      let arrFields = document.querySelectorAll(
        ".entry__form input, .entry__form textarea"
      );
      arrFields.forEach((field) => {
        objItem[field.name] = this.activeEntry[field.name];
      });
      if (this.activeItem.entries == undefined) {
        this.activeItem.entries = [];
      }
      blnNewEntry && this.activeEntries.unshift(objItem);
      this.entryClose();
      this.saveData(process.env.VUE_APP_DATA);
    },
    entryDelete: function(id) {
      if (confirm("Are you sure?")) {
        let thisEntry = this.listItemMatch(id, this.activeEntries)[1];
        this.activeEntries.splice(thisEntry, 1);
      }
    },
    listItemMatch: function(id, list) {
      let intListLength = list.length;
      for (var i = 0; i < intListLength; i++) {
        if (list[i].id == id) {
          return [list[i], i];
        }
      }
    },
    loadData: function(type){
      switch(type) {
        case "local":
          this.loadFromLocal()
          break;
        case "db":
          this.loadFromDatabase()
          break;
        default:
      } 
    },
    saveData: function(type){
      switch(type) {
        case "local":
          this.saveToLocal()
          break;
        case "db":
          this.saveToDatabase()
          break;
        default:
      } 
    },
    loadFromLocal: function() {
      let objSavedData = localStorage.getItem("growData");
      if (objSavedData != null) {
        objSavedData = JSON.parse(objSavedData);
        this.growList = objSavedData.arrGrowList;
        objSavedData.arrGrowList.length > 0 && (this.intLastId = objSavedData.intLastId);
      }
    },
    saveToLocal: function() {
      let objGrowData = {
        arrGrowList: this.growList,
        intLastId: this.intLastId,
      };
      localStorage.setItem("growData", JSON.stringify(objGrowData));
    },
    loadFromDatabase: function() {
      var username = this.$auth.user.email;
      username = "timbarden@outlook.com";

      var xhr = new XMLHttpRequest();
      xhr.overrideMimeType("application/json");
      xhr.onreadystatechange = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {       
          var strSavedData = xhr.responseText;
          strSavedData = JSON.parse(strSavedData);
          this.growList = strSavedData.arrGrowList;
          strSavedData.arrGrowList.length > 0 && (this.intLastId = strSavedData.intLastId);
        }
      };
      xhr.open("GET", process.env.VUE_APP_API_URL + "dataLoad.php?username=" + username, true);
      xhr.send();
    },
    saveToDatabase: function(){
      let objGrowData = {
          'arrGrowList': this.growList,
          'intLastId': this.intLastId,
        },
        strGrowData = JSON.stringify(objGrowData);
      strGrowData = encodeURIComponent(strGrowData);
      var xhr = new XMLHttpRequest();
      xhr.open("POST", process.env.VUE_APP_API_URL + "dataSave.php", false);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send("username=" + this.$auth.user.email + "&growlist=" + strGrowData);
    },
    formatDate: function(date) {
      if (date) {
        let d = new Date(date);
        return (
          d.getDate() +
          " " +
          this.arrMonths[d.getMonth()] +
          " " +
          d.getFullYear()
        );
      }
      return "";
    },
    dateDifference: function(sowdate, harvestdate) {
      var diffD = Math.floor(
        (new Date(harvestdate) - new Date(sowdate)) / (1000 * 60 * 60 * 24) / 7
      );
      return " (" + diffD + " weeks)";
    },
    groupBy: function(array, key) {
      this.objGroups = {};
      this.objGroups[""] = [];
      array.forEach((item) => {
        if (!this.objGroups[item[key]]) {
          this.objGroups[item[key]] = [];
        }
        this.objGroups[item[key]].push(item);
      });
      return this.objGroups;
    },
  },
  computed: {
    groups() {
      return this.groupBy(this.growList, "type");
    },
    sownDirect: function() {
      return this.activeEntry.sowtype;
    },
    sortList: function() {
      if (this.growList.length != 0){
        return this.growList.slice(0).sort((a, b) => a.name.localeCompare(b.name));
      }
      return this.growList;
    },
    sortEntries: function() {
      if (this.activeEntries.length != 0){
        // .slice(0) removes 'Unexpected side effects'
        return this.activeEntries.slice(0).sort((a, b) => b.sowdate.localeCompare(a.sowdate));
      }
      return this.activeEntries;
    },
  },
  mounted() {
    this.loadData(process.env.VUE_APP_DATA);
  },
};
</script>

<style lang="scss">
/* FORM ELEMENTS */
form {
  margin: 0;
}
label {
  display: block;
  margin: 0 0 0.25em;
}
input:not([type="color"]),
textarea {
  padding: 0.5rem 0.7rem;
}
input,
textarea,
select {
  border-radius: 5px;
  width: 100%;
  font: inherit;
  color: inherit;
  margin-bottom: 1.5rem;
  border: 1px solid #ddd;
}
textarea {
  resize: none;
}
select {
  width: 9em;
  padding: 0.15em 0.3em;
  border-color: #555;
}
input {
  &[name="name"] {
    font-size: 1.75em;
    font-weight: 700;
  }
  &[type="date"] {
    width: 10em;
  }
  &[type="color"] {
    width: 3.5em;
    height: 3.5em;
    padding: 0;
    z-index: 0;
    overflow: hidden;
    border-radius: 50%;
    transform: scale(0.9);
    transform-origin: 0 50%;
    flex-shrink: 0;
  }
}
fieldset {
  display: flex;
  > div {
    display: flex;
    align-items: center;
  }
  label {
    padding: 0;
    margin: 0 1.25em 0 .5em;
  }
  input[type="radio"]{
    margin: 0;
  }
}
::-webkit-color-swatch,
::-moz-color-swatch {
  border: none;
}
button {
  font: inherit;
  border: 0;
  outline: 0;
  cursor: pointer;
  -webkit-appearance: none;
  -webkit-border-radius: 0;
  border-radius: 5px;
  padding: 0.45em 0.7em;
  background: #eee;
  transition: background 0.1s ease;
  &:hover {
    background: #ddd;
  }
  &.btn--sq {
    width: 2em;
    height: 2em;
    padding: 0;
  }
  img {
    width: 1.25em;
    height: 1.25em;
  }
}

/* PLANNER */
.planner {
  padding: 2em 4vw;
  &__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    padding-bottom: 0.5em;
  }
  &__title {
    font-size: 1.25em;
    margin: 1.5em 0 0;
  }
  &__item__btns {
    flex-shrink: 0;
  }
}

@media screen and (min-width: 900px) {
  .planner {
    width: 50%;
  }
}

/* ITEM EDITOR  */
.item {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
}
.item__box {
  width: 90vw;
  height: 90vh;
  max-width: 600px;
  max-height: 500px;
  position: relative;
  background: #FFF;
  border: 1px solid #FFF;
  overflow: hidden;
  border-radius: 5px;
  &__home {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    bottom: 0;
    overflow: auto;
    &__form {
      &__header {
        color: #FFF;
        background: #222;
        display: flex;
        justify-content: space-between;
        padding: 1em 1.5em 0.65em;
        border-bottom: 4px solid #222;
        &__name {
          width: 100%;
          padding-right: 1em;
        }
        input {
          margin: 0;
          padding-left: 0;
          background: transparent;
          border-color: rgba(#FFF, 0);
          &:hover {
            border-color: rgba(#FFF, 0.3);
          }
        }
      }
      &__main {
        padding: 1.5em 1.5em 0;
        select {
          background: transparent;
          color: #bbb;
        }
      }
    }
    &__main {
      padding: 1.5em 1.5em 0;
    }
  }
}
.item__btns {
  padding: 0 1.5em;
  margin-bottom: 2em;
  display: flex;
  justify-content: flex-end;
  button {
    margin-left: 0.5em;
  }
}
.item__entries {
  &__header {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    justify-content: space-between;
    h3 {
      font-size: 1.25em;
    }
  }
  &__list {
    list-style: none;
    margin: 0 0 1.5em;
    padding: 0;
    li {
      border: 1px solid #ddd;
      margin-top: 1em;
      padding: 1em;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-radius: 5px;
      p {
        margin: 0.25em 0 0;
        overflow: hidden;
        max-height: 1.25em;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
      }
      div > span {
        opacity: 0.3;
        font-size: 0.8em;
      }
    }
    &__btns {
      display: flex;
      flex-shrink: 0;
      margin-left: 0.5em;
      button {
        margin-left: 0.5em;
      }
    }
  }
}

/* ENTRY */
.entry {
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
  padding: 1.5em;
  background: #FFF;
  overflow: auto;
  box-shadow: 0 0 0 1em rgba(0, 0, 0, 0.2);
  &__form {
    &__notes {
      width: 100%;
    }
    &__dates {
      display: flex;
      flex-wrap: wrap;
    }
  }
  &__btns {
    margin-bottom: 2em;
    display: flex;
    justify-content: flex-end;
    button {
      margin-left: 0.5em;
    }
  }
}
.fade-enter-active, 
.fade-leave-active {
  transition: opacity .2s;
}
.fade-enter, 
.fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>

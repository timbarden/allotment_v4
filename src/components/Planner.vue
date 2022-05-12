<template>
  <main class="main">
    <section class="planner">
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
          @itemDelete="itemDelete(item)"
        />
      </ul>

      <div class="planner__welcome" v-if="!$auth.isAuthenticated">
        <p>Welcome! You need to log in.</p>
      </div>
    </section>

    <!-- sidebar -->
    <div class="sidebar" v-if="$auth.isAuthenticated">
      <ToDoBoard
        :growList="growList"
        :objBoards="objBoards"
        :itemShow="itemShow"
      />
    </div>

    <transition name="fadeup">
      <div class="item" v-if="$auth.isAuthenticated && blnItemOpen">
        <div class="item__box" :class="{ entryactive: blnEntryOpen }">
          <div class="item__box__home">
            <header class="item__box__home__header">
              <form
                class="item__box__home__header__form"
                action=""
                :style="{ 'border-color': activeItem.color }"
                @submit.prevent
              >
                <input
                  type="text"
                  name="name"
                  v-model="activeItem['name']"
                  placeholder="Item name"
                />
                <input
                  type="color"
                  name="color"
                  v-model="activeItem['color']"
                />
              </form>
            </header>
            <main class="item__box__home__main">
              <form
                class="item__box__home__main__form"
                action=""
                :style="{ 'border-color': activeItem.color }"
                @submit.prevent
              >
                <select name="type" v-model="activeItem['type']">
                  <option value="" disabled>Type:</option>
                  <option
                    v-for="(name, index) in arrGroups"
                    :key="index"
                    :value="name"
                    >{{ name }}</option
                  >
                </select>
              </form>
              <div class="item__entries">
                <header class="item__entries__header">
                  <h3>Entries</h3>
                  <button @click="entryShow()">Add entry</button>
                </header>
                <ul class="item__entries__list">
                  <li
                    class="item__entries__list__item"
                    v-for="entry in sortEntries"
                    :key="entry.id"
                  >
                    <button
                      class="item__entries__list__item__edit"
                      @click="entryShow(entry.id)"
                    >
                      <span
                        >{{ formatDate(entry.sowdate) }}
                        <span v-if="entry.harvestdate">-</span>
                        {{ formatDate(entry.harvestdate) }}</span
                      >
                      <span v-if="entry.sowdate && entry.harvestdate">{{
                        dateDifference(entry.sowdate, entry.harvestdate)
                      }}</span>
                      <p>{{ entry.notes }}</p>
                    </button>
                    <button
                      class="item__entries__list__item__delete btn--sq"
                      @click="entryDelete(entry)"
                    >
                      <img src="../assets/trash-outline.svg" alt="Delete" />
                    </button>
                  </li>
                </ul>
              </div>
            </main>
            <footer class="item__box__home__footer item__footer">
              <div class="item__btns">
                <button class="item__btn" @click="itemClose">Cancel</button>
                <button @click="itemSave(blnItemNew)">Save</button>
              </div>
            </footer>
          </div>

          <transition name="scale">
            <div v-if="blnEntryOpen" class="item__box__home__entry entry">
              <main class="entry__main">
                <form class="entry__main__form" action="">
                  <label class="for">Notes / results</label>
                  <textarea
                    name="notes"
                    cols="30"
                    rows="6"
                    class="entry__main__form__notes"
                    v-model="activeEntry['notes']"
                  ></textarea>
                  <label>Varieties</label>
                  <input
                    type="text"
                    name="varieties"
                    v-model="activeEntry['varieties']"
                  />
                  <div class="entry__main__form__dates">
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
                          <input
                            type="radio"
                            name="success"
                            value="success_yes"
                            v-model="activeEntry['success_yes']"
                          />
                          <label>Yes</label>
                        </div>
                        <div>
                          <input
                            type="radio"
                            name="success"
                            value="success_no"
                            v-model="activeEntry['success_yes']"
                          />
                          <label>No</label>
                        </div>
                      </fieldset>
                    </div>
                  </div>
                </form>
              </main>
              <footer class="entry__footer item__footer">
                <div class="entry__btns">
                  <button @click="entryClose" class="entry__btn">Cancel</button>
                  <button @click="entrySave(blnEntryNew)" class="entry__btn">
                    Save
                  </button>
                </div>
              </footer>
            </div>
          </transition>
        </div>
      </div>
    </transition>
  </main>
</template>

<script>
import GrowItem from "../components/GrowItem.vue";
import ToDoBoard from "../components/ToDoBoard";

export default {
  data() {
    return {
      arrMonths: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
      ],
      blnItemOpen: false,
      blnItemNew: false,
      blnEntryOpen: false,
      blnEntryNew: false,
      growList: [],
      userid: null,
      activeItem: {},
      activeEntry: {},
      activeEntries: [],
      intLastId: 0,
      objGroups: {},
      arrGroups: ["Vegetables", "Flowers", "Fruit"],
      objBoards: [
        {
          title: "Sowings",
          data: "sowdate",
        },
        {
          title: "Plant out",
          data: "plantdate",
        },
        {
          title: "Harvests",
          data: "harvestdate",
        },
      ],
      arrLogs: [],
      //publicPath: process.env.BASE_URL,
    };
  },
  components: {
    GrowItem,
    ToDoBoard,
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
        this.logUpdate("New item '" + this.activeItem.name + "'", "created");
      } else {
        // retrieve existing item
        objItem = this.listItemMatch(this.activeItem.id, this.growList)[0];
        this.logUpdate("'" + objItem.name + "'", "updated");
      }
      let arrFields = document.querySelectorAll(
        ".item__box__home__header__form input, .item__box__home__header__form textarea, .item__box__home__main__form select"
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
    itemDelete: function(obj) {
      if (confirm("Are you sure?")) {
        let thisItem = this.listItemMatch(obj.id, this.growList)[1];
        this.growList.splice(thisItem, 1);
        this.logUpdate("'" + obj.name + "'", "deleted");
        this.saveData(process.env.VUE_APP_DATA);
      }
    },
    logUpdate: function(stName, strMsg) {
      this.arrLogs.unshift(stName + " " + strMsg);
      this.arrLogs.length > 5 && this.arrLogs.pop();
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
        this.logUpdate(
          "New entry for '" + this.activeItem.name + "'",
          "created"
        );
      } else {
        // retrieve existing entry
        objItem = this.listItemMatch(
          this.activeEntry.id,
          this.activeEntries
        )[0];
        this.logUpdate("Entry for '" + this.activeItem.name + "'", "updated");
      }
      let arrFields = document.querySelectorAll(
        ".entry__main__form input, .entry__main__form textarea"
      );
      arrFields.forEach((field) => {
        if (field.type == "radio") {
          objItem[field.value] = this.activeEntry[field.value];
        } else {
          objItem[field.name] = this.activeEntry[field.name];
        }
      });
      if (this.activeItem.entries == undefined) {
        this.activeItem.entries = [];
      }
      blnNewEntry && this.activeEntries.unshift(objItem);
      this.entryClose();
      this.saveData(process.env.VUE_APP_DATA);
    },
    entryDelete: function(obj) {
      if (confirm("Are you sure?")) {
        let thisEntry = this.listItemMatch(obj.id, this.activeEntries)[1];
        this.activeEntries.splice(thisEntry, 1);
        this.logUpdate("Entry for '" + this.activeItem.name + "'", "deleted");
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
    loadData: function(type) {
      switch (type) {
        case "local":
          this.loadFromLocal();
          break;
        case "db":
          this.loadFromDatabase();
          break;
        default:
      }
    },
    saveData: function(type) {
      switch (type) {
        case "local":
          this.saveToLocal();
          break;
        case "db":
          this.saveToDatabase();
          break;
        default:
      }
    },
    loadFromLocal: function() {
      let objSavedData = localStorage.getItem("growData");
      if (objSavedData != null) {
        objSavedData = JSON.parse(objSavedData);
        this.growList = objSavedData.arrGrowList;
        objSavedData.arrGrowList.length > 0 &&
          (this.intLastId = objSavedData.intLastId);
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
      var xhr = new XMLHttpRequest();
      xhr.overrideMimeType("application/json");
      xhr.onreadystatechange = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
          var strResponse = xhr.responseText;
          if (strResponse != "") {
            var objResponse = JSON.parse(strResponse),
              objResponseData = JSON.parse(objResponse.data);
            this.userid = objResponse.userid;
            this.growList = objResponseData.arrGrowList;
            if (objResponseData.arrGrowList != undefined) {
              objResponseData.arrGrowList.length > 0 &&
                (this.intLastId = objResponseData.intLastId);
            }
          }
        }
      };
      xhr.open(
        "GET",
        process.env.VUE_APP_API_URL +
          "dataLoad.php?username=" +
          this.$auth.user.email +
          "&db=" +
          process.env.VUE_APP_DB +
          "&dbUser=" +
          process.env.VUE_APP_DB_USER +
          "&dbUser=" +
          process.env.VUE_APP_DB_PASS,
        true
      );
      xhr.send();
    },
    saveToDatabase: function() {
      let objGrowData = {
          arrGrowList: this.growList,
          intLastId: this.intLastId,
        },
        strGrowData = JSON.stringify(objGrowData);
      strGrowData = encodeURIComponent(strGrowData);
      var strQuery =
        "username=" + this.$auth.user.email + "&growlist=" + strGrowData + "&db=" +
          process.env.VUE_APP_DB +
          "&dbUser=" +
          process.env.VUE_APP_DB_USER +
          "&dbPass=" +
          process.env.VUE_APP_DB_PASS,
      if (this.userid != null) {
        strQuery = "userid=" + this.userid + "&" + strQuery;
      }
      var xhr = new XMLHttpRequest();
      xhr.open("POST", process.env.VUE_APP_API_URL + "dataSave.php", false);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send(strQuery);
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
      if (this.growList.length != 0) {
        return this.growList
          .slice(0)
          .sort((a, b) => a.name.localeCompare(b.name));
      }
      return this.growList;
    },
    sortEntries: function() {
      if (this.activeEntries.length != 0) {
        // .slice(0) removes 'Unexpected side effects'
        return this.activeEntries
          .slice(0)
          .sort((a, b) => b.sowdate.localeCompare(a.sowdate));
      }
      return this.activeEntries;
    },
  },
  mounted() {
    //console.log(this.$auth.loading);
    //setInterval(function(){
    //  console.log(this.$auth.loading);
    //  if (this.$auth.loading){
    //    clearInterval(authCheck);
    //  }
    //console.log("test");
    //}, 1000);
    this.loadData(process.env.VUE_APP_DATA);
  },
};
</script>

<style lang="scss">
@import "@/styles/base.scss";
@import "@/styles/transitions.scss";
.main {
  padding: 4vw;
  display: flex;
  flex-wrap: wrap;
  align-items: flex-start;
  justify-content: space-between;
}
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
  background: transparent;
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
    width: 3em;
    height: 3em;
    padding: 0;
    z-index: 0;
    overflow: hidden;
    border-radius: 50%;
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
    margin: 0 1.25em 0 0.5em;
  }
  input[type="radio"] {
    margin: 0;
  }
}
::-webkit-color-swatch,
::-moz-color-swatch {
  border: none;
}

/* PLANNER */
.planner {
  width: 100%;
  &__header {
    @extend %flex_center;
    justify-content: space-between;
    flex-wrap: wrap;
    padding-bottom: 0.5em;
    h1 {
      line-height: 1;
      margin-bottom: 0.25em;
    }
  }
  &__title {
    font-size: 1.25em;
    margin: 1.5em 0 0;
  }
  ul {
    @extend %list_reset;
  }
  &__item__btns {
    flex-shrink: 0;
  }
}

@media screen and (min-width: 900px) {
  .planner {
    flex: auto;
    width: auto;
    margin-right: 2em;
  }
}

/* ITEM EDITOR  */
.item__footer {
  position: sticky;
  bottom: 0;
  background: #fff;
  padding: 1em 1.5em;
}

.item {
  @extend %abs_fill;
  position: fixed;
  z-index: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(100, 100, 100, 0.75);
}
.item__box {
  width: 90vw;
  height: 90vh;
  max-width: 600px;
  max-height: 500px;
  position: relative;
  background: #fff;
  overflow: hidden;
  border-radius: 12px;
  &__home {
    @extend %abs_fill;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    &__header {
      position: sticky;
      top: 0;
      &__form {
        @extend %flex_center;
        justify-content: space-between;
        padding: 0.65em 1.5em;
        background: #222;
        color: #fff;
        &__name {
          width: 100%;
          padding-right: 1em;
        }
        input {
          margin: 0;
          padding-left: 0;
          background: transparent;
          border-color: rgba(#fff, 0);
          &:hover {
            border-color: rgba(#fff, 0.3);
          }
        }
      }
    }
    &__main {
      @extend %scroll_overflow;
      margin-bottom: auto;
      padding: 1.25em 1.5em 0;
      select {
        width: 100%;
        padding: 0.35em;
        background: transparent;
        border: 1px solid #ddd;
        box-shadow: 3px 3px 3px 0 rgba(0, 0, 0, 0.03);
      }
    }
  }
}
.item__btns {
  display: flex;
  justify-content: flex-end;
  button {
    margin-left: 0.5em;
  }
}
.item__entries {
  margin-top: 1em;
  &__header {
    @extend %flex_center;
    flex-wrap: wrap;
    justify-content: space-between;
    h3 {
      font-size: 1.25em;
    }
  }
  &__list {
    @extend %list_reset;
    margin-bottom: 1.5em;
    &__item {
      @extend %flex_center;
      justify-content: space-between;
      margin-top: 1.25em;
      border-radius: 5px;
      position: relative;
      &__edit {
        width: 100%;
        background: transparent;
        border-radius: inherit;
        text-align: left;
        border: 1px solid #ddd;
        padding: 1.25em 4.25em 1.25em 1.25em;
        box-shadow: 3px 3px 3px 0 rgba(0, 0, 0, 0.03);
        transition: box-shadow 0.2s ease;
        &:hover {
          box-shadow: 3px 3px 3px 0 rgba(0, 0, 0, 0.1);
        }
        > span {
          opacity: 0.3;
          font-size: 0.8em;
          white-space: nowrap;
        }
        > p {
          margin: 0.25em 0 0;
        }
      }
      &__delete {
        position: absolute;
        top: 0;
        right: 1em;
        bottom: 0;
        margin: auto 0;
      }
    }
  }
}

/* ENTRY */
.entry {
  position: absolute;
  top: 12px;
  right: 12px;
  left: 12px;
  bottom: 12px;
  background: #fff;
  border-radius: 7px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  box-shadow: 0 0 0 1em rgba(0, 0, 0, 0.2);
  &__main {
    @extend %scroll_overflow;
    padding: 1.5em;
    &__form {
      &__notes {
        width: 100%;
      }
      &__dates {
        display: flex;
        flex-wrap: wrap;
      }
    }
  }
  &__btns {
    display: flex;
    justify-content: flex-end;
    button {
      margin-left: 0.5em;
    }
  }
}
.log {
  width: 100%;
  margin-top: 2em;
  background: linear-gradient(#f3f3f3, #ddd);
  border-radius: 5px;
  padding: 1.5em;
  ol {
    margin: 1em 0 0;
    padding: 0;
    list-style: none;
    display: flex;
    flex-direction: column;
    &:hover {
      button {
        opacity: 0.5;
      }
    }
    li {
      margin: 0.15em 0;
      pointer-events: none;
      &:not(:first-child) button {
        border-top: 1px solid #ddd;
      }
      button {
        @extend %btn_reset;
        pointer-events: auto;
        padding: 0.45em 0 0.25em;
        transition: opacity 0.2s ease;
        &:hover {
          opacity: 1;
        }
      }
      span {
        opacity: 0.8;
        font-size: 0.75em;
      }
    }
  }
}

@media screen and (min-width: 900px) {
  .sidebar {
    position: sticky;
    top: 94px;
  }
  .log {
    width: 24em;
    min-height: 15em;
    margin-top: 1.15em;
  }
}
</style>

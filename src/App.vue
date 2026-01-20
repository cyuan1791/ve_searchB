<script setup>
/*
 1. At the manageserverLib.py:summaryFile() create '/home/cmsnow/www/hosts/depo/WebCMSNow/{host}/scripts/data/summaryFile.json' file.
 2. In the v3_search.py, it reads the summaryFile.json file and encodes it to base64 and injects it to window.asoneSummary variable.
 3. Here, we decode the base64 string and parse it to JSON object.
 4. Then, we use this data to create a search interface.

*/
import { ref, onMounted } from "vue";

let asoneSummary = JSON.parse(atob(window.asoneSummary));
let asoneRatingSummary = JSON.parse(atob(window.asoneRatingSummary));
let asoneLoc = window.asoneLoc;
//console.log(asoneSummary);
//let asoneType = ref(Object.keys(asoneSummary)[0]);
let asoneType = ref("");
let seartchText = ref("e");
let asoneTypeNumber = ref(0);
let missingModuleTypeName = ref("");
const websiteInstance = ref(0);
const unqueWebsites = ref(0);
//let asoneModuleTypeName = {
//  oneColumnPost: "Post",
//  v3_shop: "Shop",
//  b_videoPagination: "Classes",
//};
console.log(window.asoneModuleTypeName);
let asoneModuleTypeNameObj = window.asoneModuleTypeName;

let asoneModuleTypeName = Object.entries(window.asoneModuleTypeName).sort(
  (a, b) => {
    // Sort by the value (index 1) using localeCompare for strings
    return a[1][0].localeCompare(b[1][0]);
  }
);

//console.log(asoneModuleTypeName);
//console.log(asoneSummary);
function getMyRating(page_path) {
  //return "";
  console.log(page_path);
  if (page_path in asoneRatingSummary) {
    let numberOfRatings = asoneRatingSummary[page_path][0];
    let totalRatings = asoneRatingSummary[page_path][1];
    let averageRating = totalRatings / numberOfRatings;
    let fullStars = Math.floor(averageRating);
    let halfStar = averageRating - fullStars >= 0.5 ? 1 : 0;
    //let emptyStars = 5 - fullStars - halfStar;
    let averageRatingText =
      " " + averageRating.toFixed(2) + " (" + numberOfRatings + " ratings)";
    averageRating = averageRatingText;
    for (let i = 0; i < fullStars; i++) {
      averageRating =
        '<i class=" fas fa-star" style="color: #ffcc00;"></i>' + averageRating;
    }
    return averageRating;
  } else {
    return '<i class=" fas fa-star" style="color: #a0a0a0;"></i><i class=" fas fa-star" style="color: #a0a0a0;"></i><i class=" fas fa-star" style="color: #a0a0a0;"></i><i class=" fas fa-star" style="color: #a0a0a0;"></i><i class=" fas fa-star" style="color: #a0a0a0;"></i>  5.00 (0 ratings)';
  }
}

//console.log(asoneModuleTypeName);
onMounted(() => {
  // Perform actions that require the component to be in the DOM
  for (let key in asoneSummary) {
    //console.log(`Key: ${key}`);
    if (asoneModuleTypeNameObj) {
      if (!(key in asoneModuleTypeNameObj)) {
        missingModuleTypeName.value += `Missing ${key} name mapping.\n`;
      }
    } else {
      missingModuleTypeName.value =
        "asoneModuleTypeName is missing. \nie. window.asoneModuleTypeName = { oneColumnPost: 'Post', v3_shop: 'Shop', b_videoPagination: 'Classes'};\nAdded in mdata['Multi-GPostInc']";
    }
  }
  let websiteList = [];
  let unqueWebsiteList = [];
  for (let key in asoneSummary) {
    if (!asoneType.value) {
      asoneType.value = key;
    }
    asoneSummary[key].forEach((item) => {
      if (!websiteList.includes(item[1])) {
        websiteList.push(item[1]);
      }
      let website = item[0].split(":")[0];
      if (!unqueWebsiteList.includes(website)) {
        unqueWebsiteList.push(website);
      }
    });
  }
  asoneTypeNumber.value = Object.keys(asoneSummary).length;
  websiteInstance.value = websiteList.length;
  unqueWebsites.value = unqueWebsiteList.length;
  //console.log(`Total module types: ${asoneTypeNumber.value}`);
  //console.log(unqueWebsiteList);
});

//console.log(asoneModuleTypeName);

const selectType = (type) => {
  asoneType.value = type;
};
</script>

<template>
  <div v-if="missingModuleTypeName" class="alert alert-warning" role="alert">
    <pre>{{ missingModuleTypeName }}</pre>
  </div>
  <div v-else>
    <!--
    <div
      class="d-flex justify-content-center"
      style="gap: 10px; margin-bottom: 20px"
    >
  
      <div class="p-2 bg-success-subtle rounded">
        {{ unqueWebsites }} shared websites / {{ websiteInstance }} subjects
      </div>
    </div>
  -->
    <div
      class="d-flex justify-content-center"
      style="gap: 5px; margin-bottom: 20px"
    >
      <div class="p-3 bg-info-subtle rounded" style="font-size: 12px">
        <span
          class="ps-1"
          v-for="(value, key) in asoneModuleTypeName"
          :key="key"
        >
          <span v-if="value[0] in asoneSummary">
            <a
              v-if="value[1] == 'Shop'"
              class="rounded border border-info bg-danger-subtle ps-1 text-decoration-underline"
              @click="selectType(value[0])"
              style="cursor: pointer"
              >{{ value[1][0] }}/{{ asoneSummary[value[0]].length }} ,</a
            >
            <a
              v-else
              class="ps-1 text-decoration-underline"
              @click="selectType(value[0])"
              style="cursor: pointer"
              >{{ value[1][0] }}/{{ asoneSummary[value[0]].length }} ,</a
            >
          </span>
          <span v-else>{{ value[1][0] }}/0 ,</span>
        </span>
        <!--
        <span v-for="(value, key) in asoneSummary" :key="key">
          <button class="btn btn-primary" @click="selectType(key)">
            {{ asoneModuleTypeName[key] }} / {{ asoneSummary[key].length }}
          </button>
        </span>
      --></div>
    </div>
    <div
      class="d-flex justify-content-center"
      style="gap: 10px; margin-bottom: 10px"
    >
      <span v-if="asoneType"
        >Searching in: {{ asoneModuleTypeNameObj[asoneType] }}</span
      >
      <input v-if="asoneType" v-model="seartchText" placeholder="Search..." />
    </div>
    <div
      class="d-flex flex-column align-items-center"
      style="gap: 10px; margin-bottom: 10px"
    >
      <div v-if="seartchText && asoneType">
        <div
          v-for="(item, index) in asoneSummary[asoneType]"
          :key="index"
          style="margin-left: 20px"
        >
          <div
            v-if="
              item[4].toLowerCase().includes(seartchText.toLocaleLowerCase()) ==
              true
            "
          >
            <a
              :href="`/default/${asoneLoc}/${item[1]}/#${item[2]}`"
              target="_blank"
            >
              <span
                v-html="
                  getMyRating('/default/' + asoneLoc + '/' + item[1] + '/')
                "
                style="min-width: 210px; display: inline-block"
              ></span>
              {{ item[1] }} - {{ item[4].slice(0, 50) }}
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
agray {
  color: rgb(220, 206, 206);
}
</style>

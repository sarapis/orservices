<template>
  <div class="inner_services">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-3 text-left">
              <a
                :href="route"
                class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic waves-effect waves-classic btn-info"
                >Back To Edit</a
              >
            </div>
            <div class="col-md-6">
              <!-- <VueSimpleRangeSlider
                style="width: auto"
                :min="0"
                :max="max"
                v-model="number"
              /> -->
              <vue-slide-bar
                v-model="sliderWithLabel.value"
                :data="sliderWithLabel.data"
                :range="sliderWithLabel.range"
                @callbackRange="callbackRange"
                slide
              >
                <template slot="tooltip" slot-scope="tooltip">
                  <img src="/images/rectangle-slider.svg" />
                </template>
              </vue-slide-bar>
            </div>
            <div class="col-md-3 text-right">
              <button
                type="button"
                class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn waves-effect waves-classic"
                @click="restoreClick"
              >
                Restore
              </button>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <h4 class="card-title title_edit mb-30 mt-20">
            Revisions: ({{ allAudit.length }})
          </h4>
          <div class="row">
            <div class="col-md-6 text-center">
              <h3
                style="
                  background: #dce0e5;
                  margin: 0px 0px 20px;
                  padding: 18px 12px;
                  border-radius: 10px;
                  font-size: 16px;
                  line-height: 19px;
                  font-weight: 500;
                  color: #000;
                "
              >
                {{ audit.created_at | formatDate }}
              </h3>
            </div>
            <div class="col-md-6 text-center">
              <h3
                style="
                  background: #dce0e5;
                  margin: 0px 0px 20px;
                  padding: 18px 12px;
                  border-radius: 10px;
                  font-size: 16px;
                  line-height: 19px;
                  font-weight: 500;
                  color: #000;
                "
              >
                Current
              </h3>
            </div>
          </div>

          <div class="card all_form_field">
            <div class="card-block">
              <div class="row">
                <div class="col-md-12">
                  <div
                    class="revision_div"
                    v-for="(data, index) in datas"
                    :key="index"
                  >
                    <h3>
                      {{ titleName(index) }}
                    </h3>
                    <div v-if="audit.new_values[index]" class="inner_codediv">
                      <code-diff
                        :old-string="
                          audit.old_values[index] != null
                            ? audit.old_values[index].toString()
                            : '-'
                        "
                        :new-string="data != null ? data.toString() : '-'"
                        :context="10"
                        outputFormat="side-by-side"
                      />
                    </div>
                    <div v-else class="inner_codediv">
                      <code-diff
                        :old-string="data != null ? data.toString() : '-'"
                        :new-string="data != null ? data.toString() : '-'"
                        :context="10"
                        outputFormat="side-by-side"
                        :isShowNoChange="true"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VueSlideBar from "vue-slide-bar";
import rangesliderJs from "rangeslider-js";
import CodeDiff from "vue-code-diff";
export default {
  props: ["recordid", "id"],
  components: { CodeDiff, VueSlideBar },
  data() {
    return {
      datas: [],
      audit: "",
      slider: 0,
      number: 10,
      max: 1,
      allAudit: [],
      route: "",
      sliderWithLabel: {
        value: 0,
        data: [0, 1, 2, 3],
        range: [],
        rangeValue: {},
      },
    };
  },
  mounted() {
    this.getDatas();
    rangesliderJs.create(document.getElementById("slider2"));
  },
  watch: {
    number(val) {
      console.log(val);
      if (val != 0) this.audit = this.allAudit[val - 1];
    },
  },
  methods: {
    getDatas() {
      axios.defaults.headers.common = {
        "X-Requested-With": "XMLHttpRequest",
        "X-CSRF-TOKEN": window.csrf_token,
      };
      axios.get(`/getDatas/${this.id}/${this.recordid}`).then((response) => {
        this.datas = response.data.datas;
        this.number = response.data.allAudit.length;
        this.max = this.number;

        this.allAudit = response.data.allAudit;
        this.audit = response.data.audit;
        this.route = response.data.route;
        this.sliderWithLabel.data = this.allAudit.map((v, i) => {
          if (this.id == v.id) this.sliderWithLabel.value = i;
          if (this.id == v.id) this.number = i;
          if (this.id == v.id)
            this.sliderWithLabel.range.push({
              label:
                "Revised " +
                moment(v.created_at).format("YYYY/MM/DD") +
                " by " +
                v.user.first_name +
                " " +
                (v.user.last_name != null ? v.user.last_name : ""),
            });
          else
            this.sliderWithLabel.range.push({
              label: "",
            });
          return i;
        });
        console.log(this.number);
      });
    },
    callbackRange(val) {
      this.sliderWithLabel.rangeValue = val;
      console.log(this.audit);
      setTimeout(() => {
        this.sliderWithLabel.range = [];
        this.allAudit.map((v, i) => {
          if (this.sliderWithLabel.value == i)
            this.sliderWithLabel.range.push({
              label:
                "Revised " +
                moment(v.created_at).format("YYYY/MM/DD") +
                " by " +
                v.user.first_name +
                " " +
                (v.user.last_name != null ? v.user.last_name : ""),
            });
          else
            this.sliderWithLabel.range.push({
              label: "",
            });
        });
        console.log(this.sliderWithLabel.value);
        this.audit = this.allAudit[this.sliderWithLabel.value];
      }, 500);
    },
    titleName(name) {
      return name
        .split("_")
        .map((val, index) => {
          return val.charAt(0).toUpperCase() + val.slice(1);
        })
        .join(" ");
    },
    restoreClick() {
      let id = this.audit ? this.audit.id : null;
      let modal = this.audit ? this.audit.auditable_type : null;
      if (id) {
        let data = {
          modal: modal,
        };
        axios.defaults.headers.common = {
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN": window.csrf_token,
        };
        axios
          .post(`/restoreDatas/${id}/${this.recordid}`, data)
          .then((response) => {
            this.audit = response.data.audit;
            this.number = response.data.allAudit.length;
            this.allAudit = response.data.allAudit;
            this.max = this.number;
            location.href = response.data.route;
          });
      }
    },
    highlightred(newElem, oldElem) {
      //   newElem.html(
      if (newElem == null) newElem = "";
      if (oldElem == null) oldElem = "";

      return newElem
        .split(" ")
        .map(function (val, i) {
          let old = oldElem.split(" ");
          return val != old[i]
            ? "<span class='highlightred'>" + val + "</span>"
            : val;
        })
        .join(" ");
      //   );
    },
    highlightgreen(newElem, oldElem) {
      //   newElem.html(
      if (newElem == null) newElem = "";
      if (oldElem == null) oldElem = "";
      //   return newElem
      //     .split("")
      //     .map(function (val, i) {
      //       return val != oldElem.charAt(i)
      //         ? "<span class='highlightgreen'>" + val + "</span>"
      //         : val;
      //     })
      //     .join("");
      return newElem
        .split(" ")
        .map(function (val, i) {
          let old = oldElem.split(" ");
          return val != old[i]
            ? "<span class='highlightgreen'>" + val + "</span>"
            : val;
        })
        .join(" ");
      //   );
    },
  },
};
</script>

<style>
.range_slider {
  position: relative !important;
  width: 100% !important;
  opacity: 1 !important;
  height: auto !important;
  margin-bottom: 20px;
}
.highlightred {
  color: #ea2323 !important;
}
.highlightgreen {
  color: #1c7911 !important;
}
.hljs {
  height: auto !important;
}
span > strong {
  font-weight: 600;
}
.d2h-files-diff {
  height: auto;
}
.inner_codediv .d2h-file-collapse,
.inner_codediv .d2h-file-wrapper {
  border: none;
  border-radius: 0px;
}
/* .inner_codediv {
  background: #fff;
  border-radius: 12px;
  border: 1px solid #dbdbdb;
  border: none;
  overflow: hidden;
  margin-bottom: 40px;
} */
.d2h-wrapper .d2h-code-side-line.d2h-info {
  height: 30px !important;
  line-height: 27px;
}
.hljs.arduino,
.d2h-info {
  display: none;
}
.d2h-wrapper .d2h-code-linenumber,
.d2h-code-side-linenumber {
  height: 31px;
  border: none;
}
.d2h-wrapper .d2h-file-side-diff {
  margin-bottom: 0;
  overflow-x: auto;
}
code {
  border: none !important;
}
.vue-slide-bar-separate-text[data-v-d3e7b39a] {
  font-size: 15px !important;
  font-weight: 600 !important;
  width: 150px !important;
  word-break: break-word !important;
  white-space: normal !important;
  line-height: initial;
}

.revision_div {
  border: 1px solid #a4a8b8;
  border-radius: 12px;
  margin-bottom: 20px;
}
.revision_div h3 {
  color: rgb(0, 0, 0);
  background: #edeef1;
  border-radius: 12px 12px 0px 0px;
  padding: 8px 24px;
  font-weight: 500;
  font-size: 16px;
  margin: 0px;
  font-family: "Neue Haas Grotesk Display Roman";
}
.d2h-wrapper .d2h-code-linenumber,
.d2h-code-side-linenumber,
.d2h-code-line-prefix {
  display: none;
}
.d2h-code-side-line {
  padding: 0 24px;
}
.d2h-del,
.d2h-ins {
  background: #fff;
}
code,
code span {
  font-weight: 500;
  /* font-size: 18px; */
  color: #8e8e8e !important;
  font-family: "Neue Haas Grotesk Display Roman";
}
.d2h-file-wrapper {
  margin-bottom: 0;
  padding: 0;
}
.d2h-code-line del,
.d2h-code-side-line del {
  padding: 0 4px;
  margin-left: 6px;
}
.hljs-deletion {
  background-color: #fff;
}
.d2h-files-diff .d2h-file-side-diff {
  padding: 12px 0px;
}
.d2h-files-diff .d2h-file-side-diff:first-child {
  border-right: 1px solid #a4a8b8;
  margin: 0px;
}
</style>

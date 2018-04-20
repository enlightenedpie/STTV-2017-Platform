import axios from "axios";

const API = {
  getEnglish: function() {
    return axios.get("/api/english");
  },
  getReading: function() {
    return axios.get("/api/reading");
  }
};

export default API;

import React from "react";
import { Grid, TextField, } from "@mui/material";
import { DatePicker, LocalizationProvider } from "@mui/x-date-pickers";
import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs';
import dayjs from "dayjs";

const GoodsFilter = ({ filterData, setFilterData }) => {

  const onChangeFilterData = (name, value) => {
    if (name === "startDate") {
      setFilterData({ ...filterData, ["productDate[gte]"]: dayjs(value) });
    } else {
      setFilterData({ ...filterData, [name]: value });
    }
  };

  const handleDatePickerChange = (date) => {
    onChangeFilterData("startDate", date);
  };

  return (
    <Grid container spacing={2} style={{ marginBottom: "32px" }}>
      <Grid item xs={12} sm={4}>
        <div>
          <TextField
            id="name"
            label="Name"
            variant="outlined"
            name="name"
            type="text"
            onChange={(event) => onChangeFilterData("name", event.target.value)}
            value={filterData.name || ""}
          />
        </div>
      </Grid>
      <Grid item xs={12} sm={4}>
        <div>
          <TextField
            id="minPrice"
            label="Min Price"
            variant="outlined"
            name="price[gte]"
            type="number"
            onChange={(event) => onChangeFilterData("price[gte]", event.target.value)}
            value={filterData["price[gte]"] || ""}
          />
        </div>
      </Grid>
      <Grid item xs={12} sm={4}>
        <div>
          <TextField
            id="maxPrice"
            label="Max Price"
            variant="outlined"
            name="price[lte]"
            type="number"
            onChange={(event) => onChangeFilterData("price[lte]", event.target.value)}
            value={filterData["price[lte]"] || ""}
          />
        </div>
      </Grid>
      <Grid item xs={12} sm={4}>
        
      </Grid>
      <Grid item xs={12} sm={6}>
        <LocalizationProvider dateAdapter={AdapterDayjs}>
          <div>
            <DatePicker
              id="startDate"
              name="startDate"
              value={filterData["productDate[gte]"] || ""}
              onChange={handleDatePickerChange}
              format="MM.dd.yyyy"
            />
          </div>
        </LocalizationProvider>
      </Grid>
    </Grid>
  );
};

export default GoodsFilter;

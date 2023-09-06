import React from "react";

const GoodsFilter = ({ filterData, setFilterData }) => {

  const onChangeFilterData = (event) => {
    event.preventDefault();

    let { name, value } = event.target;

    setFilterData({ ...filterData, [name]: value });
  };

  return <>
    <div>
      <label htmlFor="name">Name</label>
      <input
        id="name"
        type="text"
        name="name"
        onChange={onChangeFilterData}
        defaultValue={filterData.name ?? ""}
      />
    </div>
  </>
};

export default GoodsFilter;
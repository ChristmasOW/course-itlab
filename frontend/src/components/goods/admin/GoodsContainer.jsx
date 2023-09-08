import React, { useEffect, useState } from "react";
import axios from "axios";
import { responseStatus } from "../../../utils/consts";
import { Helmet } from "react-helmet-async";
import { NavLink, useNavigate, useSearchParams } from "react-router-dom";
import GoodsList from "./GoodsList";
import { fetchFilterData, checkFilterItem } from "../../../utils/fetchFilterData";
import userAuthenticationConfig from "../../../utils/userAuthenticationConfig";
import GoodsFilter from "./GoodsFilter";
import { Breadcrumbs, Grid, Link, Pagination, Typography, TextField, Button, FormControl } from "@mui/material";

const GoodsContainer = () => {

  const [formData, setFormData] = useState({
    productName: "",
    productPrice: "",
    productDescription: "",
    productCategory: "",
    productUser: "",
  });

  const clearFormFields = () => {
    const clearedFormData = Object.fromEntries(
      Object.keys(formData).map(key => [key, ""])
    );
    setFormData(clearedFormData);
  };

  const navigate = useNavigate();
  const [searchParams] = useSearchParams();

  const [goods, setGoods] = useState(null);

  const [filterData, setFilterData] = useState({
    "page": checkFilterItem(searchParams, "page", 1, true),
    "name": checkFilterItem(searchParams, "name", null),
    "price[gte]": checkFilterItem(searchParams, "price[gte]", null),
    "price[lte]": checkFilterItem(searchParams, "price[lte]", null),
    "productDate[gte]": checkFilterItem(searchParams, "productDate[gte]", null),
    "productDate[lte]": checkFilterItem(searchParams, "productDate[lte]", null)
  });

  const fetchProducts = () => {
    let filterUrl = fetchFilterData(filterData);

    navigate(filterUrl);

    axios.get("/api/products" + filterUrl + "&itemsPerPage=" + paginationInfo.itemsPerPage, userAuthenticationConfig()).then(response => {
      if (response.status === responseStatus.HTTP_OK && response.data["hydra:member"]) {
        setGoods(response.data["hydra:member"]);
        setPaginationInfo({
          ...paginationInfo,
          totalItems: response.data["hydra:totalItems"],
          totalPageCount: Math.ceil(response.data["hydra:totalItems"] / paginationInfo.itemsPerPage)
        });
      }
    }).catch(error => {
      console.log("error");
    });
  };

  const [paginationInfo, setPaginationInfo] = useState({
    totalItems: null,
    totalPageCount: null,
    itemsPerPage: 10
  });

  useEffect(() => {
    fetchProducts();
  }, [filterData]);

  const onChangePage = (event, page) => {
    setFilterData({ ...filterData, page: page });
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    const newProduct = {
      name: formData.productName,
      price: formData.productPrice,
      description: formData.productDescription,
      category: formData.productCategory,
      user: formData.productUser,
    };

    try {
      const token = localStorage.getItem('token');
      const response = await axios.post(
        "/api/products",
        newProduct,
        {
          headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json",
          },
        }, userAuthenticationConfig());

      if (response.status === responseStatus.HTTP_CREATED) {
        // Update the products and clear the form fields in one block
        fetchProducts();
        clearFormFields();
      }
    } catch (error) {
      console.error("Error creating a new product:", error);
    }
  };

  return (
    <>
      <Helmet>
        <title>
          Sign in
        </title>
      </Helmet>
      <Breadcrumbs aria-label="breadcrumb">
        <Link component={NavLink} underline="hover" color="inherit" to="/">
          Home
        </Link>
        <Typography color="text.primary">Goods</Typography>
      </Breadcrumbs>
      <Typography variant="h4" component="h1" mt={1}>
        Goods
      </Typography>
      <GoodsFilter
        filterData={filterData}
        setFilterData={setFilterData}
      />
      <form onSubmit={handleSubmit}>
        <Grid container spacing={2}>
          <Grid item xs={12} sm={4}>
            <TextField
              label="Product Name"
              value={formData.productName}
              onChange={(e) => setFormData({ ...formData, productName: e.target.value })}
              required
            />
          </Grid>
          <Grid item xs={12} sm={4}>
            <TextField
              label="Price"
              value={formData.productPrice}
              onChange={(e) => setFormData({ ...formData, productPrice: e.target.value })}
              required
            />
          </Grid>
          <Grid item xs={12} sm={4}>
            <TextField
              label="Description"
              value={formData.productDescription}
              onChange={(e) => setFormData({ ...formData, productDescription: e.target.value })}
              required
            />
          </Grid>
          <Grid item xs={12} sm={4}>
            <TextField
              label="Category"
              value={formData.productCategory}
              onChange={(e) => setFormData({ ...formData, productCategory: e.target.value })}
              required
            />
          </Grid>
          <Grid item xs={12} sm={4}>
            <TextField
              label="User"
              value={formData.productUser}
              onChange={(e) => setFormData({ ...formData, productUser: e.target.value })}
              required
            />
          </Grid>
          <Grid item xs={12} sm={4}>
            <Button type="submit" variant="contained" color="primary">
              Add Product
            </Button>
          </Grid>
        </Grid>
      </form>
      <GoodsList
        goods={goods}
      />
      {paginationInfo.totalPageCount &&
        <Pagination
          count={paginationInfo.totalPageCount}
          shape="rounded"
          page={filterData.page}
          onChange={(event, page) => onChangePage(event, page)}
        />}
    </>
  );
};

export default GoodsContainer;

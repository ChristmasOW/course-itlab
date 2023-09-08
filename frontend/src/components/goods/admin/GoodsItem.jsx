import React from "react";
import format from "date-fns/format";
import Card from "@mui/material/Card";
import CardContent from "@mui/material/CardContent";
import Typography from "@mui/material/Typography";
import Box from "@mui/material/Box";

const GoodsItem = ({ good }) => {
  const productDate = new Date(parseInt(good.productDate, 10));

  const formattedDate = format(productDate, "MM.dd.yyyy");

  return (
    <Card variant="outlined">
      <CardContent>
        <Box display="flex" flexDirection="row" gap={5} alignItems="center">
          <Typography variant="h5" component="div">
            {good.name}
          </Typography>
          <Typography variant="body2" color="text.secondary">
            Description: {good.description}
          </Typography>
          <Typography variant="body2" color="text.secondary">
            Price: {good.price}
          </Typography>
          <Typography variant="body2" color="text.secondary">
            Category: {good.categoryName}
          </Typography>
          <Typography variant="body2" color="text.secondary">
            Date: {formattedDate}
          </Typography>
        </Box>
      </CardContent>
    </Card>
  );
};

export default GoodsItem;
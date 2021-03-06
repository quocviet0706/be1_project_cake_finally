<?php
class OrderDetail extends Db
{
    static function getAllOrder()
    {
        $sql = self::$connection->prepare("SELECT * FROM ordersdetail");
        $sql->execute();
        $items = array();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items; //return an array.
    }


    static function getOrder_ByOrderId($orderId)
    {
        $sql = self::$connection->prepare("SELECT * FROM ordersdetail WHERE orderid = ?");
        $sql->bind_param('i', $orderId);
        $sql->execute();
        $items = array();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items; //return an array.
    }

    static function getOrder_ByProductId($productId)
    {
        $sql = self::$connection->prepare("SELECT * FROM ordersdetail WHERE productid = ?");
        $sql->bind_param('i', $productId);
        $sql->execute();
        $items = array();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items; //return an array.
    }

    static function getOrder_Product($productId, $orderId)
    {
        $sql = self::$connection->prepare("SELECT * FROM ordersdetail WHERE productid = ? AND orderid = ?");
        $sql->bind_param('ii', $productId, $orderId);
        $sql->execute();
        $items = array();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items; //return an array.
    }

    static function insertOrder($orderId, $productId, $quantity, $price)
    {
        $sql = self::$connection->prepare("INSERT INTO ordersdetail(orderid, productid, quantity, price) VALUES (?,?,?,?)");
        $sql->bind_param('iiii', $orderId, $productId, $quantity, $price);
        return $sql->execute();
    }

    static function updateCart($orderId, $productId, $quantity, $price)
    {
        $sql = self::$connection->prepare("UPDATE ordersdetail SET quantity = $quantity, price = $price WHERE productid = $productId AND orderid = $orderId");
        return $sql->execute();
    }

    static function removeProduct_ById($orderId, $productId)
    {
        $sql = self::$connection->prepare("DELETE FROM ordersdetail WHERE productid = $productId AND orderid = $orderId");
        return $sql->execute();
    }

    static function removeAll()
    {
        $sql = self::$connection->prepare("DELETE FROM ordersdetail");
        return $sql->execute();
    }

    static function removeAll_ByOrderId($orderId)
    {
        $sql = self::$connection->prepare("DELETE FROM ordersdetail WHERE orderid = $orderId");
        return $sql->execute();
    }

    static function searchProduct_ByOrderId($orderId)
    {
        $sql = self::$connection->prepare("SELECT * FROM ordersdetail WHERE orderid = $orderId");
        $sql->execute();
        $items = array();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items; //return an array.
    }

    static function searchProduct_ByOrderIdAndCreatePaginate($orderId, $page, $resultsPerPage)
    {
        //T??nh xem n??n b???t ?????u hi???n th??? t??? trang c?? s??? th??? t??? l?? bao nhi??u:
        $firstLink = ($page - 1) * $resultsPerPage; //(Trang hi???n t???i - 1) * (S??? k???t qu??? hi???n th??? tr??n 1 trang).
        //D??ng LIMIT ????? gi???i h???n s??? l?????ng k???t qu??? ???????c hi???n th??? tr??n 1 trang:
        $sql = self::$connection->prepare("SELECT * FROM ordersdetail WHERE orderid = $orderId LIMIT $firstLink, $resultsPerPage");
        $sql->execute();
        $items = array();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items; //return an array.
    }

    static function paginate($url, $page, $totalResults, $resultsPerPage, $offset)
    {
        $totalLinks = ceil($totalResults / $resultsPerPage);
        $links = "";
        $from = $page - $offset;
        $to = $page + $offset;
        if ($from <= 0) {
            $from = 1;
            $to = $offset * 2;
        }
        if ($to > $totalLinks) {
            $to = $totalLinks;
        }
        $firstLink = "";
        $lastLink = "";
        $prevLink = "";
        $nextLink = "";
        // Tr?????ng h???p ????? xu???t hi???n $firstLink, $lastLink, $prevLink, $nextLink:
        if($page > 1) {
            $prev = $page - 1;
            $prevLink = "<a style=\"padding:10px;\" href='$url" . "page=$prev'>< Previous</a>";
            $firstLink = "<a style=\"padding:10px;\" href='$url" . "page=1'><< First</a>";
        }
        if($page < $totalLinks) {
            $next = $page + 1;
            $nextLink = "<a style=\"padding:10px;\" href='$url" . "page=$next'>Next ></a>";
            $lastLink = "<a style=\"padding:10px;\" href='$url" . "page=$totalLinks'>Last >></a>";
        }
        // $links:
        for($i=$from; $i<=$to; $i++) {
            if($page == $i) {
                $links = $links . "<a style=\"padding:10px;text-decoration:underline;color:red;font-weight:bold;\" href='$url" . "page=$i'>$i</a>";
            }
            else
            {
                $links = $links . "<a style=\"padding:10px;\" href='$url" . "page=$i'>$i</a>";
            }
        }
        return $firstLink . $prevLink . $links . $nextLink . $lastLink;
    }
}

<?php

namespace Contact\Model;


use Contact\Entity\ImageInterface;

interface ImageModelInterface
{
    /**
     * Retrieve all images for given Contact ID
     *
     * @param int $contactId
     * @return ImageInterface[]
     */
    public function fetchAllImages($contactId);

    /**
     * Find an image by given Image ID
     *
     * @param int $contactId
     * @param int $imageId
     * @return ImageInterface
     */
    public function findImageById($contactId, $imageId);

    /**
     * Store an Image in the backend
     *
     * @param ImageInterface $image
     * @return ImageInterface
     */
    public function saveImage($contactId, ImageInterface $image);

    /**
     * Remove an Image from the backend
     *
     * @param ImageInterface $image
     * @return bool
     */
    public function deleteImage(ImageInterface $image);
}
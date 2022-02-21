<?php 
    require_once("crud/usercrud.class.php");
   

    class UniqueIdGenerator {
    /**********************************
     * Unique ID generator, used as an extra security measure,
     * specifically against sequential ID attacks.
     * Takes in the desired id type as a string and calls the correct function
     * depending on argument.
     * Automatically sets the column name as required for the table.
     * UID retrieved by calling the getUniqueId function after instantiation.
     **********************************************************/
        
        private $existingIds = []; // Array of retrieved Ids
        private $column;            // Column index, used for iterating through id array
        private $length;            // Length of the uid
        private $UNIQUE_ID;         // final generated uid


        /**********************
         * Takes the type of id as string
         * valid arguments --
         *      userid
         *      productid
         *      cartid
         * Returns the object instance to allow method chaining
         *****************************/
        public function setIdProperties($passedIds, $length=20) {
            $this->setIdLength($length);
            $this->setExistingIds($passedIds);
            $this->generateNewUniqueId();

            return $this;
        }


        private function setIdLength($length) { $this->length = $length; }
        private function setExistingIds($existingIds) { $this->existingIds = $existingIds; }

        private function getLength() { return $this->length; }
        private function getExistingIds() { return $this->existingIds; }
        
        /*****************************************************************************
         * Generates a unique id using the hexadecimal conversion of a random set of 20 bytes
         * loops through the retrievedIds array, using the column index, to check
         * if the id already exists in the table.
         * Once a unique id is generated the UNIQUE_ID instance variable is populated
         ****************************************************************************/
        private function generateNewUniqueId() {
            $uniqueId = "";
            do {
                $uniqueId = bin2hex(random_bytes($this->getLength()));
                $unique = 1;

                foreach($this->getExistingIds() as $id) {
                    if ($uniqueId == $id) {
                        $unique = 0;
                        break;
                    }
                }
            } while ($unique == 0);

            $this->UNIQUE_ID = $uniqueId;
        }


        // Returns the unique id
        public function getUniqueId(): string {
            return $this->UNIQUE_ID;
        }
    }

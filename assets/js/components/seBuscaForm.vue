<template>
    <div>
        <el-form ref="form" :model="form" v-loading="loading">
            <div class="row">
                <div class="col-12 col-lg-12">
                    <el-form-item label="Titulo">
                        <el-input v-model="form.title" placeholder="Titulo"></el-input>
                    </el-form-item>
                </div>
                <div class="col-12 col-lg-12">
                    <el-form-item label="Descripcion">
                        <el-input type="textarea" v-model="form.description" placeholder="Descripcion" :rows="6"></el-input>
                    </el-form-item>
                </div>
                <div class="col-12 col-lg-12">
                    <el-form-item label="Imagenes">
                    </el-form-item>
                    <el-upload :auto-upload="false" :action="uploadendpoint" ref="upload" list-type="picture-card" accept="image/*" :multiple="true"
                    :on-preview="handlePictureCardPreview" :on-remove="handleRemove" :limit="6" :on-exceed="handleExceed" :on-success="handleSuccess">
                        <i class="el-icon-plus"></i>
                    </el-upload>
                </div>
                <div class="col-12 col-lg-12">
                    <el-form-item class="mt-lg-3">
                        <el-button type="primary" @click="uploadImages">Crear</el-button>
                        <el-button>Cancel</el-button>
                    </el-form-item>
                </div>
                
            </div>
        </el-form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        uploadendpoint: {
            default: "product/image-upload"
        },
        submit: {
            default: "product/image-upload"
        },
    },
    data () {
        return {
            loading: false,
            form: {},
            files: [],
        }
    },
    methods: {
      handleRemove(file, fileList) {
        console.log(file, fileList);
      },
      handlePictureCardPreview(file) {
      },
      handleExceed(files, fileList) {
          this.$message.warning(`El límite es 6, haz seleccionado ${files.length} archivos esta vez, añade hasta ${files.length + fileList.length}`);
      },
      handleSuccess (response, file, fileList) {
          this.files.push({
              name: response.filename
          })
          if (this.files.length >= fileList.length) {
              console.log("Finished!", this.files)
              this.sendForm("form");
          }
      },
      uploadImages () {
          this.loading = true
          this.$refs.upload.submit();
      },
      sendForm (formName) {
          this.$refs[formName].validate((valid) => {
            if (valid) {
                let formData = new FormData ();
                formData.append("title", this.form.title);
                formData.append("description", this.form.title);
                formData.append("images", JSON.stringify(this.files));

                axios.post(this.submit, formData).then(res => {
                    console.log("Resposse", res)
                    window.location.reload()
                }).finally(() => {
                    this.loading = false;
                })
            } else {
                console.log('error submit!!');
                return false;
            }
        });
      },
      resetForm(formName) {
        this.$refs[formName].resetFields();
      }
    }
}
</script>
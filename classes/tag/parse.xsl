<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:output method="xml"/>


	<!-- match all nodes -->
	<xsl:template match="@* | node()">
		<xsl:copy>
			<xsl:apply-templates select="@* | node()"/>
		</xsl:copy>
	</xsl:template>


	<!-- ************************************* -->
	<!-- ************************************* -->
	<!-- match form nodes -->
	<!-- ************************************* -->
	<!-- ************************************* -->
<!-- 	<xsl:template match="form">

		<xsl:apply-templates select="@label"/>

		<form class="form_form">
			<xsl:copy-of select="."/>
		</form>

	</xsl:template> -->


	<!-- ************************************* -->
	<!-- ************************************* -->
	<!-- match select nodes -->
	<!-- ************************************* -->
	<!-- ************************************* -->
	<xsl:template match="checkbox">

		<div class="form_line">
			<xsl:apply-templates select="@label | @legend"/>
	

			<input type="checkbox">
				<xsl:attribute name="type">
					<xsl:value-of select="'checkbox'"/>
				</xsl:attribute>

				<xsl:attribute name="cond">
					<xsl:value-of select="@cond"/>
				</xsl:attribute>

				<xsl:attribute name="name">
					<xsl:value-of select="@name"/>
				</xsl:attribute>

				<xsl:attribute name="mandatory">
					<xsl:value-of select="@mandatory"/>
				</xsl:attribute>
			</input>
		</div>

	</xsl:template>


	<!-- ************************************* -->
	<!-- ************************************* -->
	<!-- match input nodes -->
	<!-- ************************************* -->
	<!-- ************************************* -->
	<xsl:template match="input">

		<div class="form_line form_cell">
			<xsl:apply-templates select="@label | @legend"/>

			<input class="form_input">

				<xsl:attribute name="type">
					<xsl:value-of select="'text'"/>
				</xsl:attribute>

				<xsl:attribute name="cond">
					<xsl:value-of select="@cond"/>
				</xsl:attribute>

				<xsl:attribute name="value">
					<xsl:value-of select="@source"/>
				</xsl:attribute>

				<xsl:if test="@mandatory">
					<xsl:attribute name="mandatory">
						<xsl:value-of select="'mandatory'"/>
					</xsl:attribute>
				</xsl:if>

				<xsl:if test="@check">
					<xsl:attribute name="check">
						<xsl:value-of select="@check"/>
					</xsl:attribute>
				</xsl:if>

			</input>
		</div>

	</xsl:template>


	<!-- ************************************* -->
	<!-- ************************************* -->
	<!-- match textarea nodes -->
	<!-- ************************************* -->
	<!-- ************************************* -->
	<xsl:template match="textarea">

		<div class="form_line">
			<xsl:apply-templates select="@label | @legend"/>

			<textarea>
				<xsl:attribute name="name">
					<xsl:value-of select="@name"/>
				</xsl:attribute>

				<xsl:attribute name="cond">
					<xsl:value-of select="@cond"/>
				</xsl:attribute>

				<xsl:attribute name="mandatory">
					<xsl:value-of select="'mandatory'"/>
				</xsl:attribute>

				<xsl:value-of select="@source"/>
 				<xsl:text> </xsl:text>
			</textarea>
		</div>

	</xsl:template>


	<!-- ************************************* -->
	<!-- ************************************* -->
	<!-- match submit nodes -->
	<!-- ************************************* -->
	<!-- ************************************* -->
	<xsl:template match="submit">

		<div class="form_line">
			<!-- <xsl:apply-templates select="@label | @legend"/> -->

			<input type="submit">
				<xsl:attribute name="name">
					<xsl:value-of select="@name"/>
				</xsl:attribute>

				<xsl:attribute name="value">
					<xsl:value-of select="@label"/>
				</xsl:attribute>
			</input>
		</div>

	</xsl:template>


	<!-- ************************************* -->
	<!-- ************************************* -->
	<!-- match select nodes -->
	<!-- ************************************* -->
	<!-- ************************************* -->
	<xsl:template match="radio">

		<div class="form_line">
			<xsl:apply-templates select="@label | @legend"/>

			<xsl:apply-templates type="radio" select="option">
				<xsl:with-param name="name" select="@name"/>
			</xsl:apply-templates>
		</div>

	</xsl:template>


	<xsl:template type="radio" match="option">
		<xsl:param name="name"/>

		<xsl:if test="position() &gt; 1">
			<br/>
		</xsl:if>

		<input type="radio">
			<xsl:attribute name="name">
				<xsl:value-of select="$name"/>
			</xsl:attribute>

			<xsl:value-of select="."/>
		</input>
	</xsl:template>


	<!-- ************************************* -->
	<!-- ************************************* -->
	<!-- match select nodes -->
	<!-- ************************************* -->
	<!-- ************************************* -->
	<xsl:template match="select">

		<div class="form_line">
			<xsl:apply-templates select="@label | @legend"/>

			<select>
				<xsl:attribute name="name">
					<xsl:value-of select="@name"/>
				</xsl:attribute>

				<xsl:attribute name="mandatory">
					<xsl:value-of select="@mandatory"/>
				</xsl:attribute>

				<xsl:attribute name="cond">
					<xsl:value-of select="@cond"/>
				</xsl:attribute>

				<xsl:call-template name="add_option">
					<xsl:with-param name="data" select="@source"/>
				</xsl:call-template>
			</select>
		</div>

	</xsl:template>


	<!-- ************************************* -->
	<!-- split | separated values to option tags -->
	<xsl:template name="add_option">

		<xsl:param name="data"/>


		<!-- extract first string -->
		<xsl:variable name="content">
			<xsl:choose>

				<!-- has multiple entries -->
				<xsl:when test="contains($data,'|')">
					<xsl:value-of select="substring-before($data,'|')"/>
				</xsl:when>

				<xsl:otherwise>
					<xsl:value-of select="$data"/>
				</xsl:otherwise>

			</xsl:choose>
		</xsl:variable>


		<!-- split content and value -->
		<xsl:variable name="val">
			<xsl:call-template name="get_val">
				<xsl:with-param name="data" select="$content"/>
			</xsl:call-template>
		</xsl:variable>


		<!-- create option -->
		<option>
			<xsl:if test="$val != ''">
				<xsl:attribute name="value">
					<xsl:value-of select="$val"/>
				</xsl:attribute>
			</xsl:if>

			<xsl:call-template name="get_cont">
				<xsl:with-param name="data" select="$content"/>
			</xsl:call-template>
		</option>


		<!-- recursion -->
		<xsl:if test="contains($data,'|')">
			<xsl:call-template name="add_option">
				<xsl:with-param name="data" select="substring-after($data, '|')"/>
			</xsl:call-template>
		</xsl:if>

	</xsl:template>


	<!-- ************************************* -->
	<!-- get content from content@value -->
	<xsl:template name="get_cont">
		<xsl:param name="data"/>

		<xsl:value-of select="substring-before($data,'@')"/>
	</xsl:template>


	<!-- ************************************* -->
	<!-- get content from content@value -->
	<xsl:template name="get_val">
		<xsl:param name="data"/>

		<xsl:value-of select="substring-after($data,'@')"/>
	</xsl:template>


	<!-- ************************************* -->
	<!-- remove source attribute -->
	<xsl:template match="@source">
	</xsl:template>


	<!-- ************************************* -->
	<!-- add label -->
	<xsl:template match="@label">
		<xsl:if test=". != ''">
			<div class="form_label">
				<xsl:value-of select="."/>
			</div>
		</xsl:if>
	</xsl:template>

	<!-- ************************************* -->
	<!-- add label -->
	<xsl:template match="@legend">

		<xsl:if test=". != ''">
			<div class="form_legend">
				<xsl:value-of select="."/>
			</div>
		</xsl:if>

	</xsl:template>


</xsl:stylesheet>

